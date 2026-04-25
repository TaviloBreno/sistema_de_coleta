<?php

namespace App\Application\UseCases\CollectionEvent;

use App\Domain\Repositories\CollectionEventRepositoryInterface;
use Exception;

class DeleteCollectionEventUseCase
{
    private CollectionEventRepositoryInterface $repository;

    public function __construct(CollectionEventRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): bool
    {
        $existing = $this->repository->findById($id);

        if (!$existing) {
            throw new Exception("Evento de coleta nao encontrado.");
        }

        return $this->repository->delete($id);
    }
}
