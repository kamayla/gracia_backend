<?php
namespace App\Service\Anniversaries;

use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;
use App\Repository\AnniversariesRepository;

class GetAnniversariesSortByMonthService
{
    /**
     * @var AnniversariesRepository
     */
    private $anniversariesRepository;

    public function __construct(AnniversariesRepository $anniversariesRepository)
    {
        $this->anniversariesRepository = $anniversariesRepository;
    }
    /**
     * 現在の月を開始地点として記念日の日付をソートして返す処理
     *
     * @param string $userId 認証済みのUserのユニークID
     * @param int $startMonth 開始月
     * @return array
     */
    public function execute(string $userId, int $startMonth): array
    {
        $anniversaries = $this->anniversariesRepository->getSortMonthByUserId($userId, $startMonth);

        if (count($anniversaries) > 0) {
            return $this->aggregatedByMonth($anniversaries);
        }
        return [];
    }

    /**
     * 月別に記念日をkeyにまとめる処理
     *
     * @param object $anniversaries
     * @return array
     */
    private function aggregatedByMonth(object $anniversaries): array
    {
        foreach ($anniversaries as $anniversary) {
            switch ($anniversary->month) {
                case '1':
                    $monthlyData['jan'][] = $anniversary;
                    break;
                case '2':
                    $monthlyData['feb'][] = $anniversary;
                    break;
                case '3':
                    $monthlyData['mar'][] = $anniversary;
                    break;
                case '4':
                    $monthlyData['arp'][] = $anniversary;
                    break;
                case '5':
                    $monthlyData['may'][] = $anniversary;
                    break;
                case '6':
                    $monthlyData['june'][] = $anniversary;
                    break;
                case '7':
                    $monthlyData['july'][] = $anniversary;
                    break;
                case '8':
                    $monthlyData['aug'][] = $anniversary;
                    break;
                case '9':
                    $monthlyData['sep'][] = $anniversary;
                    break;
                case '10':
                    $monthlyData['oct'][] = $anniversary;
                    break;
                case '11':
                    $monthlyData['nov'][] = $anniversary;
                    break;
                case '12':
                    $monthlyData['dec'][] = $anniversary;
                    break;
            }
        }

        return $monthlyData;
    }
}
