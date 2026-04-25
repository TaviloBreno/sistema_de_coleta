<?php

namespace App\Models;

use CodeIgniter\Model;

class CollectionRequestModel extends Model
{
    protected $table            = 'collection_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'collection_route_id',
        'collection_point_id',
        'title',
        'description',
        'scheduled_at',
        'status',
    ];

    protected $useTimestamps = true;
}
