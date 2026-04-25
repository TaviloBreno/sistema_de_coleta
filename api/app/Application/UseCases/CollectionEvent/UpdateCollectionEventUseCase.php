<?php

namespace App\Application\UseCases\CollectionEvent;

use App\Application\DTOs\CollectionEvent\UpdateCollectionEventInputDTO;
use App\Domain\Entities\CollectionEvent;
use App\Domain\Repositories\CollectionEventRepositoryInterface;
use Exception;

class UpdateCollectionEventUseCase
{
    private CollectionEventRepositoryInterface $repository;

    public function __construct(CollectionEventRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UpdateCollectionEventInputDTO $input): CollectionEvent
    {
        $existing = $this->repository->findById($input->id);

        if (!$existing) {
            throw new Exception("Evento de coleta nao encontrado.");
        }

        $updated = $this->repository->update($input->id, $input->toArray());

        if (!$updated) {
            throw new Exception("Falha ao atualizar evento de coleta.");
        }

        return $updated;
    }
}
