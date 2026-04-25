<?php

namespace App\Controllers\Api\V1;

use App\Libraries\CollectionRequestService;
use App\Models\CollectionRequestModel;
use App\Validation\CollectionRequestValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CollectionRequestsController extends ResourceController
{
    protected $modelName = CollectionRequestModel::class;
    protected $format    = 'json';

    private CollectionRequestService $service;

    public function __construct()
    {
        $this->service = new CollectionRequestService();
    }

    public function index()
    {
        return $this->respond($this->service->listForUser(service('authContext')->user()));
    }

    public function show($id = null)
    {
        $request = $this->service->findForUser((int) $id, service('authContext')->user());

        if ($request === null) {
            return $this->failNotFound('Solicitacao de coleta nao encontrada.');
        }

        return $this->respond($request);
    }

    public function create()
    {
        $rules = (new CollectionRequestValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $payload = $this->request->getJSON(true) ?? [];
        $payload['user_id'] = service('authContext')->userId();

        $id = $this->model->insert($payload);

        return $this->respondCreated($this->service->findForUser((int) $id, service('authContext')->user()), 'Solicitacao de coleta criada com sucesso.');
    }

    public function update($id = null)
    {
        $request = $this->service->findForUser((int) $id, service('authContext')->user());

        if ($request === null) {
            return $this->failNotFound('Solicitacao de coleta nao encontrada.');
        }

        $rules = (new CollectionRequestValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $payload = $this->request->getJSON(true) ?? [];
        $this->model->update($id, $payload);

        return $this->respond($this->service->findForUser((int) $id, service('authContext')->user()), ResponseInterface::HTTP_OK, 'Solicitacao de coleta atualizada com sucesso.');
    }

    public function delete($id = null)
    {
        $request = $this->service->findForUser((int) $id, service('authContext')->user());

        if ($request === null) {
            return $this->failNotFound('Solicitacao de coleta nao encontrada.');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['id' => (int) $id], 'Solicitacao de coleta excluida com sucesso.');
    }

    public function options($id = null)
    {
        return $this->response->setStatusCode(ResponseInterface::HTTP_NO_CONTENT);
    }
}
