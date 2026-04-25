<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\CollectionEvent;

interface CollectionEventRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?CollectionEvent;
    public function findByRecordId(int $recordId): array;
    public function save(CollectionEvent $event): CollectionEvent;
    public function update(int $id, array $data): ?CollectionEvent;
    public function delete(int $id): bool;
}
