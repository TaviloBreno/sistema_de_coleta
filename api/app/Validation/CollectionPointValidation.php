<?php

namespace App\Validation;

class CollectionPointValidation
{
    public function getRules(): array
    {
        return [
            'collection_route_id' => [
                'label' => 'Rota',
                'rules' => 'required|is_natural_no_zero|is_not_unique[collection_routes.id]',
            ],
            'name' => [
                'label' => 'Ponto de coleta',
                'rules' => 'required|max_length[128]',
            ],
            'address' => [
                'label' => 'Endereco',
                'rules' => 'required|max_length[170]',
            ],
            'contact_name' => [
                'label' => 'Contato',
                'rules' => 'permit_empty|max_length[128]',
            ],
            'contact_phone' => [
                'label' => 'Telefone',
                'rules' => 'permit_empty|max_length[20]',
            ],
            'scheduled_time' => [
                'label' => 'Horario programado',
                'rules' => 'permit_empty|regex_match[/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/]',
                'errors' => [
                    'regex_match' => 'O campo {field} deve estar no formato HH:MM.',
                ],
            ],
            'notes' => [
                'label' => 'Observacoes',
                'rules' => 'permit_empty|max_length[1000]',
            ],
        ];
    }
}
