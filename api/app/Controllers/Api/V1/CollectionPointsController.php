<?php

namespace App\Controllers\Api\V1;

use App\Models\CollectionPointModel;
use App\Validation\CollectionPointValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CollectionPointsController extends ResourceController
{
    protected $modelName = CollectionPointModel::class;
    protected $format    = 'json';

    public function index()
    {
        $routeId = $this->request->getGet('collection_route_id');

        $builder = $this->model->orderBy('name', 'ASC');

        if ($routeId !== null && $routeId !== '') {
            $builder->where('collection_route_id', (int) $routeId);
        }

        return $this->respond($builder->findAll());
    }

    public function byRoute($routeId = null)
    {
        if ($routeId === null || !ctype_digit((string) $routeId)) {
            return $this->failValidationErrors(['collection_route_id' => 'ID de rota invalido.']);
        }

        $points = $this->model
            ->where('collection_route_id', (int) $routeId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return $this->respond($points);
    }

    public function show($id = null)
    {
        $point = $this->model->find($id);

        if ($point === null) {
            return $this->failNotFound('Ponto de coleta nao encontrado.');
        }

        return $this->respond($point);
    }

    public function create()
    {
        $rules = (new CollectionPointValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true) ?? [];
        $id = $this->model->insert($input);

        return $this->respondCreated($this->model->find($id), 'Ponto de coleta criado com sucesso.');
    }

    public function update($id = null)
    {
        if ($this->model->find($id) === null) {
            return $this->failNotFound('Ponto de coleta nao encontrado.');
        }

        $rules = (new CollectionPointValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true) ?? [];
        $this->model->update($id, $input);

        return $this->respond($this->model->find($id), ResponseInterface::HTTP_OK, 'Ponto de coleta atualizado com sucesso.');
    }

    public function delete($id = null)
    {
        if ($this->model->find($id) === null) {
            return $this->failNotFound('Ponto de coleta nao encontrado.');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['id' => (int) $id], 'Ponto de coleta excluido com sucesso.');
    }

    public function options($id = null)
    {
        return $this->response->setStatusCode(ResponseInterface::HTTP_NO_CONTENT);
    }
}
