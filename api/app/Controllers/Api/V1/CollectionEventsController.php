<?php

namespace App\Controllers\Api\V1;

use App\Application\DTOs\CollectionEvent\CreateCollectionEventInputDTO;
use App\Application\DTOs\CollectionEvent\UpdateCollectionEventInputDTO;
use App\Application\UseCases\CollectionEvent\CreateCollectionEventUseCase;
use App\Application\UseCases\CollectionEvent\DeleteCollectionEventUseCase;
use App\Application\UseCases\CollectionEvent\GetCollectionEventUseCase;
use App\Application\UseCases\CollectionEvent\ListCollectionEventsUseCase;
use App\Application\UseCases\CollectionEvent\UpdateCollectionEventUseCase;
use App\Infrastructure\Persistence\CI4CollectionEventRepository;
use App\Validation\CollectionEventValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class CollectionEventsController extends ResourceController
{
    protected $format = 'json';
    private CI4CollectionEventRepository $repository;

    public function __construct()
    {
        // Ideally this should be injected by a DI Container
        $this->repository = new CI4CollectionEventRepository();
    }

    public function index()
    {
        $recordId = $this->request->getGet('collection_record_id');
        $useCase = new ListCollectionEventsUseCase($this->repository);
        
        $events = $useCase->execute($recordId !== null && $recordId !== '' ? (int) $recordId : null);
        
        $response = array_map(fn($e) => $e->toArray(), $events);
        return $this->respond($response);
    }

    public function byRecord($recordId = null)
    {
        if ($recordId === null || !ctype_digit((string) $recordId)) {
            return $this->failValidationErrors(['collection_record_id' => 'ID de registro invalido.']);
        }

        $useCase = new ListCollectionEventsUseCase($this->repository);
        $events = $useCase->execute((int) $recordId);

        $response = array_map(fn($e) => $e->toArray(), $events);
        return $this->respond($response);
    }

    public function show($id = null)
    {
        try {
            $useCase = new GetCollectionEventUseCase($this->repository);
            $event = $useCase->execute((int) $id);
            return $this->respond($event->toArray());
        } catch (Exception $e) {
            return $this->failNotFound($e->getMessage());
        }
    }

    public function create()
    {
        $rules = (new CollectionEventValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = new CreateCollectionEventInputDTO($this->request->getJSON(true) ?? []);
        
        try {
            $useCase = new CreateCollectionEventUseCase($this->repository);
            $event = $useCase->execute($input);
            return $this->respondCreated($event->toArray(), 'Evento de coleta criado com sucesso.');
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function update($id = null)
    {
        $rules = (new CollectionEventValidation())->getRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = new UpdateCollectionEventInputDTO((int) $id, $this->request->getJSON(true) ?? []);
        
        try {
            $useCase = new UpdateCollectionEventUseCase($this->repository);
            $event = $useCase->execute($input);
            return $this->respond($event->toArray(), ResponseInterface::HTTP_OK, 'Evento de coleta atualizado com sucesso.');
        } catch (Exception $e) {
            if ($e->getMessage() === 'Evento de coleta nao encontrado.') {
                return $this->failNotFound($e->getMessage());
            }
            return $this->failServerError($e->getMessage());
        }
    }

    public function delete($id = null)
    {
        try {
            $useCase = new DeleteCollectionEventUseCase($this->repository);
            $useCase->execute((int) $id);
            return $this->respondDeleted(['id' => (int) $id], 'Evento de coleta excluido com sucesso.');
        } catch (Exception $e) {
            return $this->failNotFound($e->getMessage());
        }
    }

    public function options($id = null)
    {
        return $this->response->setStatusCode(ResponseInterface::HTTP_NO_CONTENT);
    }
}
