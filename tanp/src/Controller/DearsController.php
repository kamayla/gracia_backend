<?php
namespace App\Controller;

use App\Controller\AppController;
use Firebase\JWT\JWT;
use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;
use App\Repository\DearsRepository;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DearsController extends AppController
{
    /**
     * @var DearsRepository
     */
    private $dearRepository;

    public $paginate = [
        'maxLimit' => 10
    ];

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow([]);
        $this->dearRepository = new DearsRepository();
    }

    public function store()
    {
        $request = $this->request->getData();

        $dear = $this->Dears->newEntity([
            'name' => $request['name'],
            'gender' => $request['gender'],
            'age' => $request['age'],
            'segment' => $request['segment'],
            'user_id' => $this->Auth->user('id'),
        ]);

        $errors = $dear->errors();

        if ($errors) {
            $this->set([
                'success' => false,
                'dear' => $dear,
                'errors' => $errors,
                '_serialize' => ['success', 'dear', 'errors']
            ]);
        } else {
            if ($this->request->is('post')) {
                $dear = $this->dearRepository->createDear($dear);
                $this->set([
                    'success' => true,
                    'dear' => $dear,
                    '_serialize' => ['success', 'dear']
                ]);
            }
        }
    }

    public function edit($id = null)
    {
        $request = $this->request->getData();
        $dear = $this->Dears->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['put'])) {
            $dear = $this->Dears->patchEntity($dear, [
                'name' => $request['name'],
                'gender' => $request['gender'],
                'age' => $request['age'],
                'segment' => $request['segment'],
                'user_id' => $this->Auth->user('id'),
            ]);
            if ($this->Dears->save($dear)) {
                $this->set([
                    'success' => true,
                    'dear' => $dear,
                    '_serialize' => ['success', 'dear']
                ]);
            } else {
                $this->response->statusCode(400);
                $errors = $dear->errors();
                $this->set([
                    'success' => false,
                    'dear' => $dear,
                    'errors' => $errors,
                    '_serialize' => ['success', 'dear', 'errors']
                ]);
            }
        }
    }

    public function list()
    {
        if ($this->request->is('get')) {
            $userId = $this->Auth->user('id');
            $dears = TableRegistry::getTableLocator()->get('Dears');
            $query = $dears->find()->contain(['Anniversaries'])->where(['user_id' => $userId])->order(['id' => 'desc']);
            $paginate = $this->paginate($query);
            $this->set([
                'data' => $paginate,
                'pagination' => $this->request->param('paging')['Dears'],
                '_serialize' => ['data', 'pagination']
            ]);
        }
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        $dear = $this->Dears->get($id);
        if ($this->Dears->delete($dear)) {
            $this->set([
                'success' => true,
                'dear' => $dear,
                '_serialize' => ['success', 'dear']
            ]);
        } else {
            $this->response->statusCode(400);
                $errors = $dear->errors();
                $this->set([
                    'success' => false,
                    'dear' => $dear,
                    'errors' => $errors,
                    '_serialize' => ['success', 'dear', 'errors']
                ]);
        }
    }
}
