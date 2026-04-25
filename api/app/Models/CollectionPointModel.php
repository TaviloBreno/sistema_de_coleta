<?php

namespace App\Models;

use CodeIgniter\Model;

class CollectionPointModel extends Model
{
    protected $table            = 'collection_points';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'collection_route_id',
        'name',
        'address',
        'contact_name',
        'contact_phone',
        'scheduled_time',
        'notes',
    ];

    protected $useTimestamps = true;
}
