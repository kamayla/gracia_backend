<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use App\Repository\UsersRepository;
use App\Model\Entity\User;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersRepositoryTest extends TestCase
{
    /**
     * @var UsersRepository
     */
    private $usersRepository;
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->usersRepository = new UsersRepository();
        parent::setUp();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testCreateUser(): void
    {
        $user = new User([
            'username' => '上村一平',
            'password' => 'a',
            'email' => 'ippei@gmail.com',
        ]);
        $dear2 = new User([
            'username' => '上村一平',
            'password' => 'aaaaaaaa',
            'email' => 'ippei@gmail.com',
        ]);
        $user = $this->usersRepository->createUser($user);
        $dear2 = $this->usersRepository->createUser($dear2);
        $this->assertSame($user->username, '上村一平');
        $this->assertSame($user->email, 'ippei@gmail.com');
        $this->assertNull($dear2);
    }

    public function testGetUserById():void
    {
        $user = $this->usersRepository->getUserById('1');
        $this->assertTrue($user instanceof User);
    }
}
