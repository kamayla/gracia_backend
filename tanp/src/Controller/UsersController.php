<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['login', 'register']);
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if (!$user) {
                throw new UnauthorizedException('Invalid email or password');
            }
            $this->set([
                'success' => true,
                'data' => [
                    'token' => JWT::encode([
                        'sub' => $user['id'],
                        'exp' =>  null,
                    ],
                    Security::salt())
                ],
                '_serialize' => ['success', 'data']
            ]);
        }
    }

    public function me()
    {
        $id = $this->Auth->user('id');
        $user = $this->Users->get($id, [
            'contain' => ['Dears', 'Dears.Anniversaries']
        ]);
        $this->set([
            'user'=> $user,
            '_serialize'=> 'user'
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set([
            'user'=>$user,
            '_serialize'=> 'user'
        ]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function register()
    {
        $request = $this->request->getData();
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, [
                'username' => $request['username'],
                'email' => $request['email'],
                'password' => (new DefaultPasswordHasher)->hash($request['password']),
            ]);
            if ($this->Users->save($user)) {
                $this->set([
                    'success' => true,
                    'data' => [
                        'token' => JWT::encode([
                            'sub' => $user['id'],
                            'exp' =>  null,
                        ],
                        Security::salt())
                    ],
                    '_serialize' => ['success', 'data']
                ]);
            } else {
                $this->response->statusCode(400);
                $this->set([
                    'success' => false,
                    'data' => null,
                    '_serialize' => ['success', 'data']
                ]);
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
