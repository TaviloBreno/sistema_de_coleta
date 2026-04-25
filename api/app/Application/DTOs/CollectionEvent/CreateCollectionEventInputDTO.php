<?php

namespace App\Application\DTOs\CollectionEvent;

class CreateCollectionEventInputDTO
{
    public int $collectionRecordId;
    public string $eventType;
    public string $title;
    public string $eventAt;
    public ?string $description;

    public function __construct(array $data)
    {
        $this->collectionRecordId = (int) ($data['collection_record_id'] ?? 0);
        $this->eventType = $data['event_type'] ?? '';
        $this->title = $data['title'] ?? '';
        $this->eventAt = $data['event_at'] ?? '';
        $this->description = $data['description'] ?? null;
    }
}
