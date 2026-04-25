<?php

namespace App\Application\DTOs\CollectionEvent;

class UpdateCollectionEventInputDTO
{
    public int $id;
    public ?int $collectionRecordId;
    public ?string $eventType;
    public ?string $title;
    public ?string $eventAt;
    public ?string $description;

    public function __construct(int $id, array $data)
    {
        $this->id = $id;
        $this->collectionRecordId = isset($data['collection_record_id']) ? (int) $data['collection_record_id'] : null;
        $this->eventType = $data['event_type'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->eventAt = $data['event_at'] ?? null;
        $this->description = $data['description'] ?? null;
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->collectionRecordId !== null) $data['collection_record_id'] = $this->collectionRecordId;
        if ($this->eventType !== null) $data['event_type'] = $this->eventType;
        if ($this->title !== null) $data['title'] = $this->title;
        if ($this->eventAt !== null) $data['event_at'] = $this->eventAt;
        if (array_key_exists('description', get_object_vars($this)) && $this->description !== null) $data['description'] = $this->description;
        return $data;
    }
}
