<?php
namespace App\Test\TestCase\Service\Anniversaries;

use Cake\TestSuite\TestCase;
use App\Service\Anniversaries\GetAnniversariesSortByMonthService;
use App\Repository\AnniversariesRepository;

/**
 * App\Model\Table\UsersTable Test Case
 */
class GetAnniversariesSortByMonthServiceTest extends TestCase
{
    /**
     * @var GetAnniversariesSortByMonthService
     */
    private $service;
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
        $repo = new AnniversariesRepository();
        $this->service = new GetAnniversariesSortByMonthService($repo);
        parent::setUp();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    /**
     * @dataProvider getTestCaseExecute
     *
     * @param integer $startMonth
     * @param array $expected
     * @return void
     */
    public function testExecute(int $startMonth, array $expected): void {
        $result = $this->service->execute(1, $startMonth);

        $this->assertSame($expected, array_keys($result));
    }

    public function getTestCaseExecute(): array
    {
        return [
            '1月スタート' => [
                'startMonth' => 1,
                'expected' => [
                    'jan',
                    'feb',
                    'mar',
                    'arp',
                    'may',
                    'june',
                    'july',
                    'aug',
                    'sep',
                    'oct',
                    'nov',
                    'dec',
                ],
            ],
            '2月スタート' => [
                'startMonth' => 2,
                'expected' => [
                    'feb',
                    'mar',
                    'arp',
                    'may',
                    'june',
                    'july',
                    'aug',
                    'sep',
                    'oct',
                    'nov',
                    'dec',
                    'jan',
                ],
            ],
            '5月スタート' => [
                'startMonth' => 5,
                'expected' => [
                    'may',
                    'june',
                    'july',
                    'aug',
                    'sep',
                    'oct',
                    'nov',
                    'dec',
                    'jan',
                    'feb',
                    'mar',
                    'arp',
                ],
            ],
            '12月スタート' => [
                'startMonth' => 12,
                'expected' => [
                    'dec',
                    'jan',
                    'feb',
                    'mar',
                    'arp',
                    'may',
                    'june',
                    'july',
                    'aug',
                    'sep',
                    'oct',
                    'nov',
                ],
            ],
        ];
    }
}
