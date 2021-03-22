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
    private const PER_PAGE = 10;
    /**
     * @var DearsRepository
     */
    private $dearRepository;

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
                $dear = $this->dearRepository->saveDear($dear);
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
        $dear = $this->Dears->get($id);
        $dear = $this->Dears->patchEntity($dear, [
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
                'errors' => $errors,
                '_serialize' => ['success', 'dear', 'errors']
            ]);
        } else {
            if ($this->request->is(['put'])) {
                $dear = $this->dearRepository->saveDear($dear);
                if ($dear) {
                    $this->set([
                        'success' => true,
                        'dear' => $dear,
                        '_serialize' => ['success', 'dear']
                    ]);
                } else {
                    $this->response->statusCode(400);
                    $this->set([
                        'success' => false,
                        'dear' => null,
                        '_serialize' => ['success', 'dear']
                    ]);
                }
            }
        }
    }

    public function list()
    {
        if ($this->request->is('get')) {
            $paginate = $this->dearRepository->listDearByPaginate(
                $this->Auth->user('id'),
                $this->request->query('page') ?? '1',
                self::PER_PAGE
            );
            $this->set([
                'data' => $paginate['data'],
                'pagination' => $paginate['pagination']['Dears'],
                '_serialize' => ['data', 'pagination']
            ]);
        }
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        $isDelete = $this->dearRepository->deleteDear($id);
        if ($isDelete) {
            $this->set([
                'success' => true,
                '_serialize' => ['success']
            ]);
        } else {
            $this->response->statusCode(400);
            $this->set([
                'success' => false,
                '_serialize' => ['success']
            ]);
        }

    }
}
