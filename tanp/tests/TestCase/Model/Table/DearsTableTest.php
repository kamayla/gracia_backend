<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DearsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DearsTableTest Test Case
 */
class DearsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DearsTable
     */
    public $Dears;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.dears'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::exists('Dears') ? [] : ['className' => DearsTable::class];
        $this->Dears = TableRegistry::get('Dears', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Dears);

        parent::tearDown();
    }

    /**
     * @dataProvider getTestCaseForValidation
     *
     * @param integer|null $user_id
     * @param string|null $name
     * @param integer|null $age
     * @param string|null $gender
     * @param string|null $segment
     * @param array $expected
     * @return void
     */
    public function testValidation(
        ?int  $user_id,
        ?string $name,
        ?int $age,
        ?string $gender,
        ?string $segment,
        array $expected
    ): void {
        $dear = $this->Dears->newEntity([
            'user_id' => $user_id,
            'name' => $name,
            'age' => $age,
            'gender' => $gender,
            'segment' => $segment,
        ]);
        $this->assertSame($expected, $dear->errors());
    }

    public function getTestCaseForValidation(): array
    {
        return [
            '全て空欄' => [
                'user_id' => null,
                'name' => null,
                'age' => null,
                'gender' => null,
                'segment' => null,
                'expected' => [
                    'user_id' => [
                        '_empty' => 'This field cannot be left empty',
                    ],
                    'name' => [
                        '_empty' => 'This field cannot be left empty',
                    ],
                    'gender' => [
                        '_empty' => 'This field cannot be left empty',
                    ],
                    'age' => [
                        '_empty' => 'This field cannot be left empty',
                    ],
                    'segment' => [
                        '_empty' => 'This field cannot be left empty',
                    ],
                ],
            ],
            '正常系' => [
                'user_id' => 1,
                'name' => '尾田栄一郎',
                'age' => 33,
                'gender' => '男性',
                'segment' => '同僚',
                'expected' => [],
            ],
        ];
    }
}
