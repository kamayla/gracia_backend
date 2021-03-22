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
        parent::tearDown();
    }

    /**
     * @dataProvider getTestCaseForCreateDear
     *
     * @param integer|null $user_id
     * @param string $name
     * @param integer|null $age
     * @param string $gender
     * @param string $segment
     * @param bool $expected
     * @return void
     */
    public function testCreateDear(
        ?int $user_id,
        string $name,
        ?int $age,
        string $gender,
        string $segment,
        bool $expected
    ): void {
        $dear = new Dear([
            'user_id' => $user_id,
            'name' => $name,
            'age' => $age,
            'gender' => $gender,
            'segment' => $segment,
        ]);
        $dear = $this->dearsRepository->createDear($dear);
        $this->assertSame($expected, $dear instanceof Dear);
    }

    public function getTestCaseForCreateDear(): array
    {
        return [
            '全て空欄' => [
                'user_id' => null,
                'name' => '',
                'age' => null,
                'gender' => '',
                'segment' => '',
                'expected' => false,
            ],
            '正常ケース' => [
                'user_id' => 1,
                'name' => '木村さん',
                'age' => 44,
                'gender' => '男性',
                'segment' => '先輩',
                'expected' => true,
            ],
        ];
    }
}
