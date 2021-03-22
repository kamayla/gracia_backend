<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Chronos\Chronos;

use App\Service\Anniversaries\GetAnniversariesSortByMonthService;
use App\Repository\AnniversariesRepository;

class AnniversariesController extends AppController
{
    /**
     * @var AnniversariesRepository
     */
    private $anniversariesRepository;

    public function initialize()
    {
        parent::initialize();
        $this->anniversariesRepository = new AnniversariesRepository();
        $this->Auth->allow([]);
    }

    public function listByDearId(string $dearId = null)
    {
        if ($this->request->is('get')) {
            $anniversaries = $this->anniversariesRepository->listByDearId($dearId);
            $this->set([
                'anniversaries' => $anniversaries,
                '_serialize' => 'anniversaries'
            ]);
        }
    }

    public function store()
    {
        $request = $this->request->getData();
        $anniversary = $this->Anniversaries->newEntity($request);
        $errors = $anniversary->errors();
        if ($errors) {
            $this->response->statusCode(400);
            $this->set([
                'success' => false,
                'anniversary' => $anniversary,
                'errors' => $errors,
                '_serialize' => ['success', 'anniversary', 'errors']
            ]);
        } elseif ($this->request->is('post')) {
            $anniversary = $this->anniversariesRepository->saveAnniversary($anniversary);
            if ($anniversary) {
                $this->set([
                    'success' => true,
                    'anniversary' => $anniversary,
                    '_serialize' => ['success', 'anniversary']
                ]);
            } else {
                $this->response->statusCode(400);
                $this->set([
                    'success' => false,
                    'anniversary' => $anniversary,
                    '_serialize' => ['success', 'anniversary']
                ]);
            }
        }
    }

    public function edit(string $id = null)
    {
        $anniversary = $this->Anniversaries->get($id);
        $anniversary = $this->Anniversaries->patchEntity(
                            $anniversary,
                            $this->request->getData()
                        );
        $errors = $anniversary->errors();
        if ($errors) {
            $this->response->statusCode(400);
            $this->set([
                'success' => false,
                'errors' => $errors,
                '_serialize' => ['success', 'anniversary', 'errors']
            ]);
        } elseif ($this->request->is(['put'])) {
            $anniversary = $this->anniversariesRepository->saveAnniversary($anniversary);
            if ($anniversary) {
                $this->set([
                    'success' => true,
                    'anniversary' => $anniversary,
                    '_serialize' => ['success', 'anniversary']
                ]);
            }
        }
    }

    public function delete(string $id = null)
    {
        $this->request->allowMethod(['delete']);
        $isDelete = $this->anniversariesRepository->deleteAnniversary($id);
        if ($isDelete) {
            $this->set([
                'success' => true,
                '_serialize' => ['success']
            ]);
        } else {
            $this->response->statusCode(400);
            $this->set([
                'success' => false,
                '_serialize' => ['success']
            ]);
        }
    }

    public function listSortByMonth()
    {
        $service = new GetAnniversariesSortByMonthService($this->anniversariesRepository);
        $this->request->allowMethod(['get']);
        $currentMonth = Chronos::now()->month;
        $anniversaries = $service->execute($this->Auth->user('id'), $currentMonth);
        $this->set([
            'anniversaries' => $anniversaries,
            '_serialize' => 'anniversaries'
        ]);
    }
}
