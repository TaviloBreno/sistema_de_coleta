<?php

namespace App\Validation;

class CollectionEventValidation
{
    public function getRules(): array
    {
        return [
            'collection_record_id' => [
                'label' => 'Registro',
                'rules' => 'required|is_natural_no_zero|is_not_unique[collection_records.id]',
            ],
            'event_type' => [
                'label' => 'Tipo de evento',
                'rules' => 'required|in_list[iniciado,andamento,concluido,ocorrencia]',
            ],
            'title' => [
                'label' => 'Titulo',
                'rules' => 'required|max_length[120]',
            ],
            'description' => [
                'label' => 'Descricao',
                'rules' => 'permit_empty|max_length[1000]',
            ],
            'event_at' => [
                'label' => 'Data do evento',
                'rules' => 'required|valid_date[Y-m-d H:i:s]',
            ],
        ];
    }
}
