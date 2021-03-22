<?php
namespace App\Repository;

use Cake\ORM\TableRegistry;
use App\Model\Table\DearsTable;
use App\Model\Entity\Dear;
use Cake\Log\LogTrait;
use Cake\Datasource\Paginator;

class DearsRepository
{
    use LogTrait;

    /**
     * @var DearsTable
     */
    private $registry;

    /**
     * @var Paginator
     */
    private $paginator;


    public function __construct()
    {
        $this->registry = TableRegistry::getTableLocator()->get('Dears');
        $this->paginator = new Paginator();
    }

    /**
     * 大切な人を保存更新する処理
     *
     * @param Dear $dear
     * @return Dear|null
     */
    public function saveDear(Dear $dear): ?Dear
    {
        try {
            $dear = $this->registry->save($dear);
            if ($dear) {
                return $dear;
            }
            return null;
        } catch (\Exception $e) {
            $this->log($e, 'error');
            return null;
        }
    }

    /**
     * ページネーションにて大切な人リストを返す処理
     *
     * @param string $userId
     * @param integer $page
     * @param integer $perPage
     * @return array
     */
    public function listDearByPaginate(
        string $userId,
        int $page,
        int $perPage
    ): array {
        $query = $this->registry->find()
                    ->contain(['Anniversaries'])
                    ->where(['user_id' => $userId])
                    ->order(['id' => 'desc']);

        $paginate = $this->paginator->paginate($query, [
            'limit' => $perPage,
            'page' => $page,
        ]);

        return [
            'data' => $paginate,
            'pagination' => $this->paginator->getPagingParams(),
        ];
    }

    /**
     * 大切な人を削除
     *
     * @param string $id
     * @return boolean
     */
    public function deleteDear(string $id): bool
    {
        try {
            $dear = $this->registry->get($id);
            $isDelete = $this->registry->delete($dear);
            return $isDelete;
        } catch (\Exception $e) {
            $this->log($e, 'error');
            return false;
        }
    }
}
