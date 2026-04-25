<?php

namespace App\Validation;

class CollectionRouteValidation
{
    public function getRules(): array
    {
        return [
            'company_id' => [
                'label' => 'Empresa',
                'rules' => 'required|is_natural_no_zero|is_not_unique[companies.id]',
                'errors' => [
                    'required' => 'O campo {field} e obrigatorio.',
                    'is_natural_no_zero' => 'O campo {field} deve conter um ID valido.',
                    'is_not_unique' => 'A empresa informada nao existe.',
                ],
            ],
            'name' => [
                'label' => 'Rota',
                'rules' => 'required|max_length[128]',
                'errors' => [
                    'required' => 'O campo {field} e obrigatorio.',
                    'max_length' => 'O campo {field} deve conter no maximo {param} caracteres.',
                ],
            ],
            'start_point' => [
                'label' => 'Ponto de inicio',
                'rules' => 'required|max_length[170]',
                'errors' => [
                    'required' => 'O campo {field} e obrigatorio.',
                    'max_length' => 'O campo {field} deve conter no maximo {param} caracteres.',
                ],
            ],
            'end_point' => [
                'label' => 'Ponto final',
                'rules' => 'required|max_length[170]',
                'errors' => [
                    'required' => 'O campo {field} e obrigatorio.',
                    'max_length' => 'O campo {field} deve conter no maximo {param} caracteres.',
                ],
            ],
            'scheduled_at' => [
                'label' => 'Data de coleta',
                'rules' => 'required|valid_date[Y-m-d H:i:s]',
                'errors' => [
                    'required' => 'O campo {field} e obrigatorio.',
                    'valid_date' => 'O campo {field} deve estar no formato YYYY-MM-DD HH:MM:SS.',
                ],
            ],
            'status' => [
                'label' => 'Status',
                'rules' => 'required|in_list[pendente,em_rota,concluida,cancelada]',
                'errors' => [
                    'required' => 'O campo {field} e obrigatorio.',
                    'in_list' => 'O campo {field} possui um valor invalido.',
                ],
            ],
            'notes' => [
                'label' => 'Observacoes',
                'rules' => 'permit_empty|max_length[1000]',
                'errors' => [
                    'max_length' => 'O campo {field} deve conter no maximo {param} caracteres.',
                ],
            ],
        ];
    }
}
