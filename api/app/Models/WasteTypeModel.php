<?php

namespace App\Models;

use CodeIgniter\Model;

class WasteTypeModel extends Model
{
    protected $table            = 'waste_types';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'unit',
        'is_hazardous',
        'description',
    ];

    protected $useTimestamps = true;
}
