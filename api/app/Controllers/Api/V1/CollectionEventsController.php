<?php

namespace App\Controllers\Api\V1;

use App\Models\CollectionEventModel;
use App\Validation\CollectionEventValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CollectionEventsController extends ResourceController
{
    protected $modelName = CollectionEventModel::class;
    protected $format    = 'json';

    public function index()
    {
        $recordId = $this->request->getGet('collection_record_id');

        $builder = $this->model->orderBy('event_at', 'DESC');

        if ($recordId !== null && $recordId !== '') {
            $builder->where('collection_record_id', (int) $recordId);
        }

        return $this->respond($builder->findAll());
    }

    public function byRecord($recordId = null)
    {
        if ($recordId === null || !ctype_digit((string) $recordId)) {
            return $this->failValidationErrors(['collection_record_id' => 'ID de registro invalido.']);
        }

        $events = $this->model
            ->where('collection_record_id', (int) $recordId)
            ->orderBy('event_at', 'DESC')
            ->findAll();

        return $this->respond($events);
    }

    public function show($id = null)
    {
        $event = $this->model->find($id);

        if ($event === null) {
            return $this->failNotFound('Evento de coleta nao encontrado.');
        }

        return $this->respond($event);
    }

    public function create()
    {
        $rules = (new CollectionEventValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true) ?? [];
        $id = $this->model->insert($input);

        return $this->respondCreated($this->model->find($id), 'Evento de coleta criado com sucesso.');
    }

    public function update($id = null)
    {
        if ($this->model->find($id) === null) {
            return $this->failNotFound('Evento de coleta nao encontrado.');
        }

        $rules = (new CollectionEventValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true) ?? [];
        $this->model->update($id, $input);

        return $this->respond($this->model->find($id), ResponseInterface::HTTP_OK, 'Evento de coleta atualizado com sucesso.');
    }

    public function delete($id = null)
    {
        if ($this->model->find($id) === null) {
            return $this->failNotFound('Evento de coleta nao encontrado.');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['id' => (int) $id], 'Evento de coleta excluido com sucesso.');
    }

    public function options($id = null)
    {
        return $this->response->setStatusCode(ResponseInterface::HTTP_NO_CONTENT);
    }
}
