<?php
namespace App\Repository;

use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;
use App\Model\Table\AnniversariesTable;

class AnniversariesRepository
{
    /**
     * @var AnniversariesTable
     */
    private $registry;


    public function __construct()
    {
        $this->registry = TableRegistry::getTableLocator()->get('Anniversaries');
    }

    /**
     * 現在の月を基軸に記念日をソートして返す
     *
     * @param string $userId 認証済みのUserのユニークID
     * @param int $startMonth 開始月
     * @return object
     */
    public function getSortMonthByUserId(string $userId, int $startMonth): object
    {
        $query = $this->registry->find()->join([
            'table' => 'dears',
            'alias' => 'dear',
            'conditions' => 'Anniversaries.dear_id = dear.id'
        ])->matching('Dears', function ($q) use ($userId) {
            return $q->where(['Dears.user_id' => $userId]);
        });
        $anniversaries = $query->select([
                                'id',
                                'kind',
                                'date',
                                'dear_name' => 'dear.name',
                                'month' => 'MONTH(date)',
                                'sort_num' => "CASE
                                            WHEN MONTH(date) < $startMonth THEN (MONTH(date) + 12)
                                            ELSE MONTH(date)
                                        END",
                            ])
                            ->order(['sort_num' => 'asc'])
                            ->all();

        return $anniversaries;
    }
}
