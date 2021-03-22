<?php
namespace App\Test\TestCase\Repository;

use App\Model\Table\AnniversariesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use App\Repository\AnniversariesRepository;
use App\Model\Entity\Anniversary;

/**
 * App\Model\Table\DearsTable Test Case
 */
class AnniversariesRepositoryTest extends TestCase
{
    /**
     * @var AnniversariesRepository
     */
    private $anniversariesRepository;
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.dears',
        'app.anniversaries'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->anniversariesRepository = new AnniversariesRepository();
        parent::setUp();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->anniversariesRepository);
        parent::tearDown();
    }

    /**
     * @dataProvider getTestCaseForSaveAnniversary
     *
     * @param integer $dear_id
     * @param string $kind
     * @param string $date
     * @param boolean $expected
     * @return void
     */
    public function testSaveAnniversary(
        ?int $dear_id,
        ?string $kind,
        ?string $date,
        bool $expected
    ): void
    {
        $anniversary = new Anniversary([
            'dear_id' => $dear_id,
            'kind' => $kind,
            'date' => $date,
        ]);
        $result = $this->anniversariesRepository->saveAnniversary($anniversary);
        $this->assertSame($expected, $result instanceof Anniversary);
    }

    public function getTestCaseForSaveAnniversary(): array
    {
        return [
            '正常系' => [
                'dear_id' => 1,
                'kind' => '結婚記念日',
                'date' => '1982-08-19',
                'expected' => true,
            ],
            '全て空欄' => [
                'dear_id' => null,
                'kind' => null,
                'date' => null,
                'expected' => false,
            ],
        ];
    }

    public function testDeleteAnniversary(): void
    {
        $isDelete = $this->anniversariesRepository->deleteAnniversary(1);
        $this->assertTrue($isDelete);
    }

    public function testListByDearId():void
    {
        $result = $this->anniversariesRepository->listByDearId(1);
        foreach ($result as $anniversary) {
            $this->assertSame(1, $anniversary->dear_id);
        }
    }

    /**
     * @dataProvider getTestCaseForGetSortMonthByUserId
     *
     * @param integer $startMonth
     * @param array $expected
     * @return void
     */
    public function testGetSortMonthByUserId(int $startMonth, array $expected): void
    {
        $result = $this->anniversariesRepository->getSortMonthByUserId(1, $startMonth);
        $aggregation = [];
        foreach ($result as $anniversary) {
            @$aggregation[$anniversary['month']] += 1;
        }

        $this->assertSame($expected, array_keys($aggregation));
    }

    public function getTestCaseForGetSortMonthByUserId(): array
    {
        return [
            '1月はじまり' => [
                'startMonth' => 1,
                'expected' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, ],
            ],
            '2月はじまり' => [
                'startMonth' => 2,
                'expected' => [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 1, ],
            ],
            '3月はじまり' => [
                'startMonth' => 3,
                'expected' => [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, ],
            ],
            '4月はじまり' => [
                'startMonth' => 4,
                'expected' => [4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3, ],
            ],
            '5月はじまり' => [
                'startMonth' => 5,
                'expected' => [5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3, 4, ],
            ],
            '6月はじまり' => [
                'startMonth' => 6,
                'expected' => [6, 7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, ],
            ],
            '7月はじまり' => [
                'startMonth' => 7,
                'expected' => [7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6, ],
            ],
            '8月はじまり' => [
                'startMonth' => 8,
                'expected' => [8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6, 7, ],
            ],
            '9月はじまり' => [
                'startMonth' => 9,
                'expected' => [9, 10, 11, 12, 1, 2, 3, 4, 5, 6, 7, 8, ],
            ],
            '10月はじまり' => [
                'startMonth' => 10,
                'expected' => [10, 11, 12, 1, 2, 3, 4, 5, 6, 7, 8, 9, ],
            ],
            '11月はじまり' => [
                'startMonth' => 11,
                'expected' => [11, 12, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, ],
            ],
            '12月はじまり' => [
                'startMonth' => 12,
                'expected' => [12, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, ],
            ],
        ];
    }
}
