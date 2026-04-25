<?php

use App\Application\DTOs\CollectionEvent\CreateCollectionEventInputDTO;
use App\Application\UseCases\CollectionEvent\CreateCollectionEventUseCase;
use App\Domain\Entities\CollectionEvent;
use App\Domain\Repositories\CollectionEventRepositoryInterface;

it('creates a collection event', function () {
    $repositoryMock = Mockery::mock(CollectionEventRepositoryInterface::class);
    
    $repositoryMock->shouldReceive('save')
        ->once()
        ->andReturnUsing(function (CollectionEvent $event) {
            return new CollectionEvent(
                $event->getCollectionRecordId(),
                $event->getEventType(),
                $event->getTitle(),
                $event->getEventAt(),
                $event->getDescription(),
                1, // generated ID
                '2026-04-25 10:00:00',
                '2026-04-25 10:00:00'
            );
        });

    $useCase = new CreateCollectionEventUseCase($repositoryMock);

    $input = new CreateCollectionEventInputDTO([
        'collection_record_id' => 1,
        'event_type' => 'COLETA_REALIZADA',
        'title' => 'Coleta',
        'event_at' => '2026-04-25 10:00:00',
        'description' => 'Descricao'
    ]);

    $result = $useCase->execute($input);

    expect($result)->toBeInstanceOf(CollectionEvent::class);
    expect($result->getId())->toBe(1);
    expect($result->getTitle())->toBe('Coleta');
});
