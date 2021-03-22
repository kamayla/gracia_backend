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
        unset($this->usersRepository);
        parent::tearDown();
    }

    /**
     * @dataProvider getTestCaseForCreateUser
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param bool $expected
     * @return void
     */
    public function testCreateUser(
        ?string $username,
        ?string $password,
        ?string $email,
        bool $expected
    ): void {
        $user = new User([
            'username' => $username,
            'password' => $password,
            'email' => $email,
        ]);
        $user = $this->usersRepository->createUser($user);
        $this->assertSame($expected, $user instanceof User);
    }

    public function getTestCaseForCreateUser(): array
    {
        return [
            '全てNull' => [
                'username' => null,
                'password' => null,
                'email' => null,
                'expected' => false,
            ],
            '正常系' => [
                'username' => '上村一平',
                'password' => 'hogehogehoge',
                'email' => 'ippei_kamimura@icloud.com',
                'expected' => true,
            ],
        ];
    }

    public function testGetUserById():void
    {
        $user = $this->usersRepository->getUserById('1');
        $this->assertTrue($user instanceof User);
    }
}
