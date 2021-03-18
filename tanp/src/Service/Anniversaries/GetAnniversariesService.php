<?php
namespace App\Service\Anniversaries;

use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;

class GetAnniversariesService
{
    public function execute()
    {
        $anniversaries = TableRegistry::getTableLocator()->get('Anniversaries');
        $query = $anniversaries->find('all', [
            'conditions'=> array('DATE_FORMAT(Anniversaries.date,"%m") = "02"')
        ]);

        $data = $query->all();

        dd($data->toArray());
    }
}
