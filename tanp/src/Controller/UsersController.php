<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use App\Repository\UsersRepository;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * @var UsersRepository
     */
    private $userRepository;

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['login', 'register']);
        $this->userRepository = new UsersRepository();
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
        $user = $this->userRepository->getUserById($id);
        $this->set([
            'user'=> $user,
            '_serialize'=> 'user'
        ]);
    }

    public function register()
    {
        if ($this->request->is('post')) {
            $user = $this->Users->newEntity($this->request->getData());
            $errors = $user->errors();
            if ($errors) {
                $this->response->statusCode(400);
                $this->set([
                    'success' => false,
                    'errors' => $errors,
                    '_serialize' => ['success', 'errors']
                ]);
            } else {
                $user = $this->userRepository->createUser($user);
                if ($user) {
                    $this->set([
                        'success' => true,
                        'data' => [
                            'token' => JWT::encode([
                                'sub' => $user->id,
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
    }
}
