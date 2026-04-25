<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\CollectionEvent;
use App\Domain\Repositories\CollectionEventRepositoryInterface;
use App\Models\CollectionEventModel;

class CI4CollectionEventRepository implements CollectionEventRepositoryInterface
{
    private CollectionEventModel $model;

    public function __construct()
    {
        $this->model = new CollectionEventModel();
    }

    public function findAll(): array
    {
        $results = $this->model->orderBy('event_at', 'DESC')->findAll();
        return array_map([$this, 'mapToEntity'], $results);
    }

    public function findById(int $id): ?CollectionEvent
    {
        $result = $this->model->find($id);
        if (!$result) {
            return null;
        }
        return $this->mapToEntity($result);
    }

    public function findByRecordId(int $recordId): array
    {
        $results = $this->model->where('collection_record_id', $recordId)
                               ->orderBy('event_at', 'DESC')
                               ->findAll();
        return array_map([$this, 'mapToEntity'], $results);
    }

    public function save(CollectionEvent $event): CollectionEvent
    {
        $data = $event->toArray();
        unset($data['id']); // Let CI4 generate it

        $id = $this->model->insert($data);
        return $this->findById($id);
    }

    public function update(int $id, array $data): ?CollectionEvent
    {
        $this->model->update($id, $data);
        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    private function mapToEntity(array $data): CollectionEvent
    {
        return new CollectionEvent(
            (int) $data['collection_record_id'],
            $data['event_type'],
            $data['title'],
            $data['event_at'],
            $data['description'] ?? null,
            (int) $data['id'],
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null,
            $data['deleted_at'] ?? null
        );
    }
}
