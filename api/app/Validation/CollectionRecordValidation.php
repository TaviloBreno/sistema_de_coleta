<?php

namespace App\Validation;

class CollectionRecordValidation
{
    public function getRules(): array
    {
        return [
            'collection_point_id' => [
                'label' => 'Ponto de coleta',
                'rules' => 'required|is_natural_no_zero|is_not_unique[collection_points.id]',
            ],
            'waste_type_id' => [
                'label' => 'Tipo de residuo',
                'rules' => 'required|is_natural_no_zero|is_not_unique[waste_types.id]',
            ],
            'quantity' => [
                'label' => 'Quantidade',
                'rules' => 'required|decimal|greater_than[0]',
            ],
            'collected_at' => [
                'label' => 'Data de coleta',
                'rules' => 'required|valid_date[Y-m-d H:i:s]',
            ],
            'notes' => [
                'label' => 'Observacoes',
                'rules' => 'permit_empty|max_length[1000]',
            ],
        ];
    }
}
