<?php

namespace App\Application\UseCases\CollectionEvent;

use App\Domain\Repositories\CollectionEventRepositoryInterface;

class ListCollectionEventsUseCase
{
    private CollectionEventRepositoryInterface $repository;

    public function __construct(CollectionEventRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(?int $recordId = null): array
    {
        if ($recordId !== null) {
            return $this->repository->findByRecordId($recordId);
        }

        return $this->repository->findAll();
    }
}
