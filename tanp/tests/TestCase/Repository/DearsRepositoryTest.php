<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DearsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use App\Repository\DearsRepository;
use App\Model\Entity\Dear;

/**
 * App\Model\Table\DearsTable Test Case
 */
class DearsRepositoryTest extends TestCase
{
    /**
     * @var DearsRepository
     */
    private $dearsRepository;
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.dears'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->dearsRepository = new DearsRepository();
        parent::setUp();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->dearsRepository);
        parent::tearDown();
    }

    /**
     * @dataProvider getTestCaseForSaveDear
     *
     * @param integer|null $user_id
     * @param string|null $name
     * @param integer|null $age
     * @param string|null $gender
     * @param string|null $segment
     * @param boolean $expected
     * @return void
     */
    public function testSaveDear(
        ?int $user_id,
        ?string $name,
        ?int $age,
        ?string $gender,
        ?string $segment,
        bool $expected
    ): void {
        $dear = new Dear([
            'user_id' => $user_id,
            'name' => $name,
            'age' => $age,
            'gender' => $gender,
            'segment' => $segment,
        ]);
        $dear = $this->dearsRepository->saveDear($dear);
        $this->assertSame($expected, $dear instanceof Dear);
    }

    public function getTestCaseForSaveDear(): array
    {
        return [
            '全てNull' => [
                'user_id' => null,
                'name' => null,
                'age' => null,
                'gender' => null,
                'segment' => null,
                'expected' => false,
            ],
            '正常系' => [
                'user_id' => 1,
                'name' => '木村さん',
                'age' => 44,
                'gender' => '男性',
                'segment' => '先輩',
                'expected' => true,
            ],
        ];
    }

    public function testListDearByPaginate(
    ): void {
        $paginate = $this->dearsRepository->listDearByPaginate(1, 1, 50);
        $this->assertSame($paginate['pagination']['Dears']['perPage'], count($paginate['data']));
    }
}
