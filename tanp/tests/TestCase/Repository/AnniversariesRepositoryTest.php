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
}
