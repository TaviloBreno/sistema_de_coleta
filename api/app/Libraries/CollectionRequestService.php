<?php

namespace App\Libraries;

use App\Models\CollectionRequestModel;

class CollectionRequestService
{
    public function listForUser(array $user): array
    {
        $builder = $this->baseQuery();

        if (($user['role'] ?? 'user') !== 'admin' && ($user['role'] ?? 'user') !== 'manager') {
            $builder->where('collection_requests.user_id', (int) $user['id']);
        }

        return $builder->orderBy('collection_requests.scheduled_at', 'DESC')->findAll();
    }

    public function findForUser(int $id, array $user): ?array
    {
        $builder = $this->baseQuery();
        $builder->where('collection_requests.id', $id);

        if (($user['role'] ?? 'user') !== 'admin' && ($user['role'] ?? 'user') !== 'manager') {
            $builder->where('collection_requests.user_id', (int) $user['id']);
        }

        return $builder->first();
    }

    public function baseQuery()
    {
        return (new CollectionRequestModel())
            ->select('collection_requests.*, collection_routes.name as route_name, collection_points.name as point_name')
            ->join('collection_routes', 'collection_routes.id = collection_requests.collection_route_id', 'left')
            ->join('collection_points', 'collection_points.id = collection_requests.collection_point_id', 'left');
    }
}
