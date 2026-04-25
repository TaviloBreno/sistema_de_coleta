<?php

namespace App\Application\UseCases\CollectionEvent;

use App\Application\DTOs\CollectionEvent\CreateCollectionEventInputDTO;
use App\Domain\Entities\CollectionEvent;
use App\Domain\Repositories\CollectionEventRepositoryInterface;

class CreateCollectionEventUseCase
{
    private CollectionEventRepositoryInterface $repository;

    public function __construct(CollectionEventRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CreateCollectionEventInputDTO $input): CollectionEvent
    {
        $event = new CollectionEvent(
            $input->collectionRecordId,
            $input->eventType,
            $input->title,
            $input->eventAt,
            $input->description
        );

        return $this->repository->save($event);
    }
}
