<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DearsController extends AppController
{
    public $paginate = [
        'maxLimit' => 10
    ];

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow([]);
    }

    public function store()
    {
        $request = $this->request->getData();
        $dear = $this->Dears->newEntity();
        if ($this->request->is('post')) {
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
            $query = $dears->find()->contain(['Anniversaries'])->where(['user_id' => $userId]);
            $paginate = $this->paginate($query);
            $this->set([
                'data' => $paginate,
                'pagination' => $this->request->param('paging')['Dears'],
                '_serialize' => ['data', 'pagination']
            ]);
        }
    }
}
