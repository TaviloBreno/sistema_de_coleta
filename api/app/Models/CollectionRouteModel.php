<?php

namespace App\Models;

use CodeIgniter\Model;

class CollectionRouteModel extends Model
{
    protected $table            = 'collection_routes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'company_id',
        'name',
        'start_point',
        'end_point',
        'scheduled_at',
        'status',
        'notes',
    ];

    protected $useTimestamps = true;
}
