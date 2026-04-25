<?php

namespace App\Controllers\Api\V1;

use App\Models\CollectionRouteModel;
use App\Validation\CollectionRouteValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CollectionRoutesController extends ResourceController
{
    protected $modelName = CollectionRouteModel::class;
    protected $format    = 'json';

    public function index()
    {
        $companyId = $this->request->getGet('company_id');

        $builder = $this->model->orderBy('scheduled_at', 'DESC');

        if ($companyId !== null && $companyId !== '') {
            $builder->where('company_id', (int) $companyId);
        }

        return $this->respond($builder->findAll());
    }

    public function byCompany($companyId = null)
    {
        if ($companyId === null || !ctype_digit((string) $companyId)) {
            return $this->failValidationErrors(['company_id' => 'ID de empresa invalido.']);
        }

        $routes = $this->model
            ->where('company_id', (int) $companyId)
            ->orderBy('scheduled_at', 'DESC')
            ->findAll();

        return $this->respond($routes);
    }

    public function show($id = null)
    {
        $route = $this->model->find($id);

        if ($route === null) {
            return $this->failNotFound('Rota de coleta nao encontrada.');
        }

        return $this->respond($route);
    }

    public function create()
    {
        $rules = (new CollectionRouteValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true) ?? [];

        $id = $this->model->insert($input);
        $route = $this->model->find($id);

        return $this->respondCreated($route, 'Rota de coleta criada com sucesso.');
    }

    public function update($id = null)
    {
        $route = $this->model->find($id);

        if ($route === null) {
            return $this->failNotFound('Rota de coleta nao encontrada.');
        }

        $rules = (new CollectionRouteValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true) ?? [];

        $this->model->update($id, $input);

        return $this->respond($this->model->find($id), ResponseInterface::HTTP_OK, 'Rota de coleta atualizada com sucesso.');
    }

    public function delete($id = null)
    {
        $route = $this->model->find($id);

        if ($route === null) {
            return $this->failNotFound('Rota de coleta nao encontrada.');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['id' => (int) $id], 'Rota de coleta excluida com sucesso.');
    }

    public function options($id = null)
    {
        return $this->response->setStatusCode(ResponseInterface::HTTP_NO_CONTENT);
    }
}
