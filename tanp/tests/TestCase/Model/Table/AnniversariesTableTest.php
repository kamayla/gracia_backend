<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnniversariesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnniversariesTableTest Test Case
 */
class AnniversariesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AnniversariesTable
     */
    public $Anniversaries;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.anniversaries'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::exists('Anniversaries') ? [] : ['className' => AnniversariesTable::class];
        $this->Anniversaries = TableRegistry::get('Anniversaries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Anniversaries);

        parent::tearDown();
    }

    /**
     * @dataProvider getTestCaseForValidation
     *
     * @param integer|null $dear_id
     * @param string|null $kind
     * @param string|null $date
     * @param array $expected
     * @return void
     */
    public function testValidation(
        ?int  $dear_id,
        ?string $kind,
        ?string $date,
        array $expected
    ): void {
        $anniversary = $this->Anniversaries->newEntity([
            'dear_id' => $dear_id,
            'kind' => $kind,
            'date' => $date,
        ]);
        $this->assertSame($expected, $anniversary->errors());
    }

    public function getTestCaseForValidation(): array
    {
        return [
            '全て空欄' => [
                'dear_id' => null,
                'kind' => null,
                'date' => null,
                'expected' => [
                    'dear_id' => [
                        '_empty' => 'This field cannot be left empty',
                    ],
                    'kind' => [
                        '_empty' => 'This field cannot be left empty',
                    ],
                    'date' => [
                        '_empty' => 'This field cannot be left empty',
                    ],
                ],
            ],
            '正常系' => [
                'dear_id' => 1,
                'kind' => '独立記念日',
                'date' => '2020-08-16',
                'expected' => [],
            ],
        ];
    }
}
