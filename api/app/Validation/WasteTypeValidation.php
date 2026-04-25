<?php

namespace App\Validation;

class WasteTypeValidation
{
    public function getRules(?int $id = null): array
    {
        return [
            'name' => [
                'label' => 'Tipo de residuo',
                'rules' => "required|max_length[100]|is_unique[waste_types.name,id,{$id}]",
            ],
            'unit' => [
                'label' => 'Unidade',
                'rules' => 'required|max_length[20]',
            ],
            'is_hazardous' => [
                'label' => 'Perigoso',
                'rules' => 'required|in_list[0,1]',
            ],
            'description' => [
                'label' => 'Descricao',
                'rules' => 'permit_empty|max_length[255]',
            ],
        ];
    }
}
