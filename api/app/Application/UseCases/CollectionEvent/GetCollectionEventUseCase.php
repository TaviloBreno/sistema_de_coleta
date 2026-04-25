<?php

namespace App\Application\UseCases\CollectionEvent;

use App\Domain\Entities\CollectionEvent;
use App\Domain\Repositories\CollectionEventRepositoryInterface;
use Exception;

class GetCollectionEventUseCase
{
    private CollectionEventRepositoryInterface $repository;

    public function __construct(CollectionEventRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): CollectionEvent
    {
        $event = $this->repository->findById($id);

        if (!$event) {
            throw new Exception("Evento de coleta nao encontrado.");
        }

        return $event;
    }
}
