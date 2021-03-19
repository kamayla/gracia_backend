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
        $dear = new User([
            'username' => '上村一平',
            'password' => 'aaaaaaaa',
            'email' => 'iiii@gmail.com',
        ]);
        $dear2 = new User([
            'username' => '上村一平',
            'password' => 'aaaaaaaa',
            'email' => 'iiii@gmail.com',
        ]);
        $dear = $this->usersRepository->createUser($dear);
        $dear2 = $this->usersRepository->createUser($dear2);

        $this->assertSame($dear->username, '上村一平');
        $this->assertSame($dear->email, 'iiii@gmail.com');
        $this->assertNull($dear2);
    }
}
