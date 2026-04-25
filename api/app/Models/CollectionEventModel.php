<?php

namespace App\Models;

use CodeIgniter\Model;

class CollectionEventModel extends Model
{
    protected $table            = 'collection_events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'collection_record_id',
        'event_type',
        'title',
        'description',
        'event_at',
    ];

    protected $useTimestamps = true;
}
