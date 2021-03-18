<?php
namespace App\Controller;

use App\Controller\AppController;
use Firebase\JWT\JWT;
use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;

use App\Service\Anniversaries\GetAnniversariesSortByMonthService;

class AnniversariesController extends AppController
{
    public $paginate = [
        'maxLimit' => 10
    ];

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow([]);
    }

    public function listByDearId(string $dearId = null)
    {
        if ($this->request->is('get')) {
            $anniversaries = TableRegistry::getTableLocator()->get('Anniversaries');
            $query = $anniversaries->find()->where(['dear_id' => $dearId])->order('id', 'desc');
            $anniversaries = $query->all();
            $this->set([
                'anniversaries' => $anniversaries,
                '_serialize' => 'anniversaries'
            ]);
        }
    }

    public function store()
    {
        $request = $this->request->getData();
        $anniversary = $this->Anniversaries->newEntity();
        if ($this->request->is('post')) {
            $anniversary = $this->Anniversaries->patchEntity($anniversary, [
                'kind' => $request['kind'],
                'date' => $request['date'],
                'dear_id' => $request['dear_id'],
            ]);
            if ($this->Anniversaries->save($anniversary)) {
                $this->set([
                    'success' => true,
                    'anniversary' => $anniversary,
                    '_serialize' => ['success', 'anniversary']
                ]);
            } else {
                $this->response->statusCode(400);
                $errors = $anniversary->errors();
                $this->set([
                    'success' => false,
                    'anniversary' => $anniversary,
                    'errors' => $errors,
                    '_serialize' => ['success', 'anniversary', 'errors']
                ]);
            }
        }
    }

    public function edit(string $id = null)
    {
        $request = $this->request->getData();
        $anniversary = $this->Anniversaries->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['put'])) {
            $anniversary = $this->Anniversaries->patchEntity($anniversary, [
                'kind' => $request['kind'],
                'date' => $request['date'],
            ]);
            if ($this->Anniversaries->save($anniversary)) {
                $this->set([
                    'success' => true,
                    'anniversary' => $anniversary,
                    '_serialize' => ['success', 'anniversary']
                ]);
            } else {
                $this->response->statusCode(400);
                $errors = $anniversary->errors();
                $this->set([
                    'success' => false,
                    'anniversary' => $anniversary,
                    'errors' => $errors,
                    '_serialize' => ['success', 'anniversary', 'errors']
                ]);
            }
        }
    }

    public function delete(string $id = null)
    {
        $this->request->allowMethod(['delete']);
        $anniversary = $this->Anniversaries->get($id);
        if ($this->Anniversaries->delete($anniversary)) {
            $this->set([
                'success' => true,
                'anniversary' => $anniversary,
                '_serialize' => ['success', 'dear']
            ]);
        } else {
            $this->response->statusCode(400);
                $errors = $anniversary->errors();
                $this->set([
                    'success' => false,
                    'anniversary' => $anniversary,
                    'errors' => $errors,
                    '_serialize' => ['success', 'anniversary', 'errors']
                ]);
        }
    }

    public function listSortByMonth(GetAnniversariesSortByMonthService $service)
    {
        $this->request->allowMethod(['get']);

        $anniversaries = $service->execute($this->Auth->user('id'));
        $this->set([
            'anniversaries' => $anniversaries,
            '_serialize' => 'anniversaries'
        ]);
    }
}
