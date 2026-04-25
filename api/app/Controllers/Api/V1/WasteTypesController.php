<?php

namespace App\Controllers\Api\V1;

use App\Models\WasteTypeModel;
use App\Validation\WasteTypeValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class WasteTypesController extends ResourceController
{
    protected $modelName = WasteTypeModel::class;
    protected $format    = 'json';

    public function index()
    {
        return $this->respond($this->model->orderBy('name', 'ASC')->findAll());
    }

    public function show($id = null)
    {
        $wasteType = $this->model->find($id);

        if ($wasteType === null) {
            return $this->failNotFound('Tipo de residuo nao encontrado.');
        }

        return $this->respond($wasteType);
    }

    public function create()
    {
        $rules = (new WasteTypeValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true) ?? [];
        $id = $this->model->insert($input);

        return $this->respondCreated($this->model->find($id), 'Tipo de residuo criado com sucesso.');
    }

    public function update($id = null)
    {
        if ($this->model->find($id) === null) {
            return $this->failNotFound('Tipo de residuo nao encontrado.');
        }

        $rules = (new WasteTypeValidation())->getRules((int) $id);

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true) ?? [];
        $this->model->update($id, $input);

        return $this->respond($this->model->find($id), ResponseInterface::HTTP_OK, 'Tipo de residuo atualizado com sucesso.');
    }

    public function delete($id = null)
    {
        if ($this->model->find($id) === null) {
            return $this->failNotFound('Tipo de residuo nao encontrado.');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['id' => (int) $id], 'Tipo de residuo excluido com sucesso.');
    }

    public function options($id = null)
    {
        return $this->response->setStatusCode(ResponseInterface::HTTP_NO_CONTENT);
    }
}
