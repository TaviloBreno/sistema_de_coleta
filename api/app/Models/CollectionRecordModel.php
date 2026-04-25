<?php

namespace App\Models;

use CodeIgniter\Model;

class CollectionRecordModel extends Model
{
    protected $table            = 'collection_records';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'collection_point_id',
        'waste_type_id',
        'quantity',
        'collected_at',
        'notes',
    ];

    protected $useTimestamps = true;
}
