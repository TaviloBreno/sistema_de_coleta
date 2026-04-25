<?php

namespace App\Domain\Entities;

class CollectionEvent
{
    private ?int $id;
    private int $collectionRecordId;
    private string $eventType;
    private string $title;
    private ?string $description;
    private string $eventAt;
    private ?string $createdAt;
    private ?string $updatedAt;
    private ?string $deletedAt;

    public function __construct(
        int $collectionRecordId,
        string $eventType,
        string $title,
        string $eventAt,
        ?string $description = null,
        ?int $id = null,
        ?string $createdAt = null,
        ?string $updatedAt = null,
        ?string $deletedAt = null
    ) {
        $this->id = $id;
        $this->collectionRecordId = $collectionRecordId;
        $this->eventType = $eventType;
        $this->title = $title;
        $this->description = $description;
        $this->eventAt = $eventAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCollectionRecordId(): int
    {
        return $this->collectionRecordId;
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEventAt(): string
    {
        return $this->eventAt;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?string
    {
        return $this->deletedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'collection_record_id' => $this->collectionRecordId,
            'event_type' => $this->eventType,
            'title' => $this->title,
            'description' => $this->description,
            'event_at' => $this->eventAt,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'deleted_at' => $this->deletedAt,
        ];
    }
}
