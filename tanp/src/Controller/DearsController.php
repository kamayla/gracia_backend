<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DearsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['login', 'register']);
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
                'anniversaries' => [
                    [
                        'kind' => $request['kind'],
                        'date' => $request['date']
                    ]
                ]
            ],
            [
                'associated' => [
                    'Anniversaries',
                ]
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
}
