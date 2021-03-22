<?php
namespace App\Repository;

use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;
use App\Model\Table\DearsTable;
use App\Model\Entity\Dear;
use Cake\Log\LogTrait;

class DearsRepository
{
    use LogTrait;
    /**
     * @var DearsTable
     */
    private $registry;


    public function __construct()
    {
        $this->registry = TableRegistry::getTableLocator()->get('Dears');
    }

    public function createDear(Dear $dear): ?Dear
    {
        try {
            $dear = $this->registry->save($dear);
            if ($dear) {
                return $dear;
            }
            return null;
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');
            return null;
        }
    }
}
