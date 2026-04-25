<?php

use App\Domain\Entities\CollectionEvent;

it('creates a collection event entity correctly', function () {
    $event = new CollectionEvent(
        1,
        'COLETA_REALIZADA',
        'Coleta de papel',
        '2026-04-25 10:00:00',
        'Foi coletado 10kg',
        10,
        '2026-04-25 10:00:00',
        '2026-04-25 10:00:00',
        null
    );

    expect($event->getId())->toBe(10);
    expect($event->getCollectionRecordId())->toBe(1);
    expect($event->getEventType())->toBe('COLETA_REALIZADA');
    expect($event->getTitle())->toBe('Coleta de papel');
    expect($event->getDescription())->toBe('Foi coletado 10kg');
    expect($event->getEventAt())->toBe('2026-04-25 10:00:00');
    expect($event->getCreatedAt())->toBe('2026-04-25 10:00:00');
    expect($event->getUpdatedAt())->toBe('2026-04-25 10:00:00');
    expect($event->getDeletedAt())->toBeNull();
});

it('converts to array correctly', function () {
    $event = new CollectionEvent(
        1,
        'COLETA_REALIZADA',
        'Coleta de papel',
        '2026-04-25 10:00:00'
    );

    $array = $event->toArray();

    expect($array['collection_record_id'])->toBe(1);
    expect($array['event_type'])->toBe('COLETA_REALIZADA');
    expect($array['title'])->toBe('Coleta de papel');
    expect($array['event_at'])->toBe('2026-04-25 10:00:00');
    expect($array['id'])->toBeNull();
    expect($array['description'])->toBeNull();
});
