<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

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
        parent::setUp();
        $config = TableRegistry::exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = TableRegistry::get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * @dataProvider getTestCaseForValidation
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @param boolean $expected
     * @return void
     */
    public function testValidation(
        string  $username,
        string $email,
        string $password,
        array $expected
    ): void {
        $user = $this->Users->newEntity([
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertSame($expected, $user->errors());
    }

    public function getTestCaseForValidation(): array
    {
        return [
            '全て空欄' => [
                'username' => '',
                'email' => '',
                'password' => '',
                'expected' => [
                    'username' => [
                        '_empty' => 'This field cannot be left empty',
                    ],
                    'password' => [
                        '_empty' => 'This field cannot be left empty',
                    ],
                    'email' => [
                        '_empty' => 'This field cannot be left empty',
                    ],
                ],
            ],
            '全て正しく入力' => [
                'username' => '上村　一平',
                'email' => 'ippei_kamimura@icloud.com',
                'password' => 'tanppass',
                'expected' => [],
            ],
        ];
    }
}
