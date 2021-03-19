<?php
namespace App\Repository;

use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;
use App\Model\Table\DearsTable;
use App\Model\Entity\Dear;

class DearsRepository
{
    /**
     * @var DearsTable
     */
    private $registry;


    public function __construct()
    {
        $this->registry = TableRegistry::getTableLocator()->get('Dears');
    }

    public function createDear(Dear $dear): Dear
    {
        $dear = $this->registry->save($dear);
        return $dear;
    }
}
