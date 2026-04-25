<?php

namespace App\Controllers\Api\V1;

use App\Models\CollectionRecordModel;
use App\Validation\CollectionRecordValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CollectionRecordsController extends ResourceController
{
    protected $modelName = CollectionRecordModel::class;
    protected $format    = 'json';

    public function index()
    {
        $pointId = $this->request->getGet('collection_point_id');

        $builder = $this->model->orderBy('collected_at', 'DESC');

        if ($pointId !== null && $pointId !== '') {
            $builder->where('collection_point_id', (int) $pointId);
        }

        return $this->respond($builder->findAll());
    }

    public function byPoint($pointId = null)
    {
        if ($pointId === null || !ctype_digit((string) $pointId)) {
            return $this->failValidationErrors(['collection_point_id' => 'ID de ponto invalido.']);
        }

        $records = $this->model
            ->where('collection_point_id', (int) $pointId)
            ->orderBy('collected_at', 'DESC')
            ->findAll();

        return $this->respond($records);
    }

    public function show($id = null)
    {
        $record = $this->model->find($id);

        if ($record === null) {
            return $this->failNotFound('Registro de coleta nao encontrado.');
        }

        return $this->respond($record);
    }

    public function create()
    {
        $rules = (new CollectionRecordValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true) ?? [];
        $id = $this->model->insert($input);

        return $this->respondCreated($this->model->find($id), 'Registro de coleta criado com sucesso.');
    }

    public function update($id = null)
    {
        if ($this->model->find($id) === null) {
            return $this->failNotFound('Registro de coleta nao encontrado.');
        }

        $rules = (new CollectionRecordValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true) ?? [];
        $this->model->update($id, $input);

        return $this->respond($this->model->find($id), ResponseInterface::HTTP_OK, 'Registro de coleta atualizado com sucesso.');
    }

    public function delete($id = null)
    {
        if ($this->model->find($id) === null) {
            return $this->failNotFound('Registro de coleta nao encontrado.');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['id' => (int) $id], 'Registro de coleta excluido com sucesso.');
    }

    public function options($id = null)
    {
        return $this->response->setStatusCode(ResponseInterface::HTTP_NO_CONTENT);
    }
}
