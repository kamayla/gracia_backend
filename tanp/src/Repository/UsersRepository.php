<?php
namespace App\Repository;

use Cake\ORM\TableRegistry;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use Cake\Log\LogTrait;

class UsersRepository
{
    use LogTrait;
    /**
     * @var UsersTable
     */
    private $registry;

    public function __construct()
    {
        $this->registry = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * userIdを基軸にUserオブジェクトを取得する
     *
     * @param string $id
     * @return object
     */
    public function getUserById(string $id): object
    {

        $user = $this->registry->find()
                    ->where(['id' => $id])
                    ->contain([
                        'Dears',
                        'Dears.Anniversaries'
                    ])
                    ->first();
        return $user;
    }

    /**
     * Userを作成しそのオブジェクトを返す処理
     *
     * @param User $user Uesrインスタンス
     * @return User 作成されたUser
     */
    public function createUser(User $user): ?User
    {
        try {
            $user = $this->registry->save($user);
            if ($user) {
                return $user;
            }
            return null;
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');
            return null;
        }
    }
}
