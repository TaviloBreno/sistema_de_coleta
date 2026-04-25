<?php

namespace App\Validation;

class CollectionRequestValidation
{
    public function getRules(): array
    {
        return [
            'collection_route_id' => [
                'label' => 'Rota',
                'rules' => 'required|is_natural_no_zero|is_not_unique[collection_routes.id]',
            ],
            'collection_point_id' => [
                'label' => 'Ponto',
                'rules' => 'required|is_natural_no_zero|is_not_unique[collection_points.id]',
            ],
            'title' => [
                'label' => 'Titulo',
                'rules' => 'required|max_length[120]',
            ],
            'description' => [
                'label' => 'Descricao',
                'rules' => 'permit_empty|max_length[1000]',
            ],
            'scheduled_at' => [
                'label' => 'Data programada',
                'rules' => 'required|valid_date[Y-m-d H:i:s]',
            ],
            'status' => [
                'label' => 'Status',
                'rules' => 'required|in_list[pendente,aprovada,em_andamento,concluida,cancelada]',
            ],
        ];
    }
}
