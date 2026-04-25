<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CrateusDemoSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $adminUserId = $this->ensureDemoUser('Administrador Crateus', 'admin@crateuslimpa.com.br', '123456', 'admin');
        $managerUserId = $this->ensureDemoUser('Gestor Operacional', 'gestor@crateuslimpa.com.br', '123456', 'manager');
        $operatorUserId = $this->ensureDemoUser('Operador de Campo', 'operador@crateuslimpa.com.br', '123456', 'user');

        $demoCompanyEmail = 'contato@ecocoletacrateus.com.br';
        $companyExists = $this->db->table('companies')->where('email', $demoCompanyEmail)->countAllResults() > 0;

        if ($companyExists) {
            return;
        }

        $this->db->table('companies')->insertBatch([
            [
                'name' => 'EcoColeta Crateus',
                'phone' => '(88) 99810-1200',
                'email' => 'contato@ecocoletacrateus.com.br',
                'address' => 'Rua Dom Pedro II, 1200 - Centro, Crateus - CE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sustenta Reciclagem Crateus',
                'phone' => '(88) 99811-2200',
                'email' => 'operacoes@sustentareciclagem.com.br',
                'address' => 'Av. Sargento Herminio, 450 - Cidade Nova, Crateus - CE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $companies = $this->db->table('companies')->orderBy('id', 'ASC')->get()->getResultArray();
        $ecoColetaId = $companies[0]['id'];
        $sustentaId = $companies[1]['id'];

        $this->db->table('collection_routes')->insertBatch([
            [
                'company_id' => $ecoColetaId,
                'name' => 'Rota Centro - Fátima',
                'start_point' => 'Galpão Central EcoColeta',
                'end_point' => 'Bairro Fátima',
                'scheduled_at' => date('Y-m-d H:i:s', strtotime('+1 day 07:30')),
                'status' => 'pendente',
                'notes' => 'Rota urbana para coleta seletiva no centro e adjacências.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'company_id' => $sustentaId,
                'name' => 'Rota Cidade Nova - Venâncios',
                'start_point' => 'Base Cidade Nova',
                'end_point' => 'Bairro Venâncios',
                'scheduled_at' => date('Y-m-d H:i:s', strtotime('+1 day 14:00')),
                'status' => 'em_rota',
                'notes' => 'Percurso com foco em pontos comerciais e grandes geradores.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $routes = $this->db->table('collection_routes')->orderBy('id', 'ASC')->get()->getResultArray();
        $rotaCentroFatimaId = $routes[0]['id'];
        $rotaCidadeNovaVenanciosId = $routes[1]['id'];

        $this->db->table('collection_points')->insertBatch([
            [
                'collection_route_id' => $rotaCentroFatimaId,
                'name' => 'Mercado Central de Crateus',
                'address' => 'Rua Dom Pedro II, 210 - Centro, Crateus - CE',
                'contact_name' => 'Marcos Lima',
                'contact_phone' => '(88) 99901-1001',
                'scheduled_time' => '07:15:00',
                'notes' => 'Ponto com maior volume de recicláveis.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'collection_route_id' => $rotaCentroFatimaId,
                'name' => 'Escola Municipal Maria Amalia',
                'address' => 'Rua Sargento Herminio, 88 - Fátima, Crateus - CE',
                'contact_name' => 'Ana Paula',
                'contact_phone' => '(88) 99902-2002',
                'scheduled_time' => '08:10:00',
                'notes' => 'Coleta de papel e plástico do intervalo escolar.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'collection_route_id' => $rotaCidadeNovaVenanciosId,
                'name' => 'Hospital Sao Lucas Crateus',
                'address' => 'Av. Dr. Silas Munguba, 500 - Cidade Nova, Crateus - CE',
                'contact_name' => 'Tereza Alves',
                'contact_phone' => '(88) 99903-3003',
                'scheduled_time' => '14:20:00',
                'notes' => 'Resíduos comuns e segregados conforme protocolo.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'collection_route_id' => $rotaCidadeNovaVenanciosId,
                'name' => 'Mercado do Venâncios',
                'address' => 'Rua Joaquim de Sousa, 320 - Venâncios, Crateus - CE',
                'contact_name' => 'Carlos Mendes',
                'contact_phone' => '(88) 99904-4004',
                'scheduled_time' => '15:05:00',
                'notes' => 'Volume variável por feira livre.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $points = $this->db->table('collection_points')->orderBy('id', 'ASC')->get()->getResultArray();
        $mercadoCentralId = $points[0]['id'];
        $escolaId = $points[1]['id'];
        $hospitalId = $points[2]['id'];
        $mercadoVenanciosId = $points[3]['id'];

        $this->db->table('waste_types')->insertBatch([
            [
                'name' => 'Papel e papelao',
                'unit' => 'kg',
                'is_hazardous' => 0,
                'description' => 'Material proveniente de comercio e escolas.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Plastico reciclavel',
                'unit' => 'kg',
                'is_hazardous' => 0,
                'description' => 'Embalagens e fracionados leves.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Residuos hospitalares comuns',
                'unit' => 'kg',
                'is_hazardous' => 1,
                'description' => 'Coleta controlada em unidade de saude.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $wasteTypes = $this->db->table('waste_types')->orderBy('id', 'ASC')->get()->getResultArray();
        $papelId = $wasteTypes[0]['id'];
        $plasticoId = $wasteTypes[1]['id'];
        $hospitalResId = $wasteTypes[2]['id'];

        $this->db->table('collection_requests')->insertBatch([
            [
                'user_id' => $managerUserId,
                'collection_route_id' => $rotaCentroFatimaId,
                'collection_point_id' => $mercadoCentralId,
                'title' => 'Solicitacao Mercado Central',
                'description' => 'Coleta adicional de papel e plastico no centro da cidade.',
                'scheduled_at' => date('Y-m-d H:i:s', strtotime('+1 day 09:00')),
                'status' => 'aprovada',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $operatorUserId,
                'collection_route_id' => $rotaCidadeNovaVenanciosId,
                'collection_point_id' => $hospitalId,
                'title' => 'Solicitacao Hospital Sao Lucas',
                'description' => 'Ajuste de agenda para residuos comuns hospitalares.',
                'scheduled_at' => date('Y-m-d H:i:s', strtotime('+1 day 15:00')),
                'status' => 'em_andamento',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $requests = $this->db->table('collection_requests')->orderBy('id', 'ASC')->get()->getResultArray();
        $requestCentroId = $requests[0]['id'];
        $requestHospitalId = $requests[1]['id'];

        $this->db->table('collection_records')->insertBatch([
            [
                'collection_point_id' => $mercadoCentralId,
                'waste_type_id' => $papelId,
                'quantity' => 124.50,
                'collected_at' => date('Y-m-d H:i:s', strtotime('-1 day 08:20')),
                'notes' => 'Volume recolhido na coleta piloto do centro.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'collection_point_id' => $hospitalId,
                'waste_type_id' => $hospitalResId,
                'quantity' => 82.75,
                'collected_at' => date('Y-m-d H:i:s', strtotime('-1 day 15:30')),
                'notes' => 'Material hospitalar segregado e transportado com sinalização.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'collection_point_id' => $mercadoVenanciosId,
                'waste_type_id' => $plasticoId,
                'quantity' => 51.20,
                'collected_at' => date('Y-m-d H:i:s', strtotime('-1 day 16:10')),
                'notes' => 'Coleta apos feira livre do bairro.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $records = $this->db->table('collection_records')->orderBy('id', 'ASC')->get()->getResultArray();

        $this->db->table('collection_events')->insertBatch([
            [
                'collection_record_id' => $records[0]['id'],
                'event_type' => 'iniciado',
                'title' => 'Coleta iniciada no Centro',
                'description' => 'Equipe saiu da base para a rota Centro - Fátima.',
                'event_at' => date('Y-m-d H:i:s', strtotime('-1 day 07:45')),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'collection_record_id' => $records[0]['id'],
                'event_type' => 'concluido',
                'title' => 'Coleta concluida no Mercado Central',
                'description' => 'Peso conferido e material enviado para triagem.',
                'event_at' => date('Y-m-d H:i:s', strtotime('-1 day 08:45')),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'collection_record_id' => $records[1]['id'],
                'event_type' => 'andamento',
                'title' => 'Coleta hospitalar em andamento',
                'description' => 'Equipe aguarda fechamento do turno da enfermaria.',
                'event_at' => date('Y-m-d H:i:s', strtotime('-1 day 15:05')),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'collection_record_id' => $records[2]['id'],
                'event_type' => 'ocorrencia',
                'title' => 'Aumento de volume no Venâncios',
                'description' => 'A feira livre gerou volume acima da media esperada.',
                'event_at' => date('Y-m-d H:i:s', strtotime('-1 day 16:25')),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    private function ensureDemoUser(string $name, string $email, string $password, string $role): int
    {
        $user = $this->db->table('users')->where('email', $email)->get()->getRowArray();
        $payload = [
            'name' => $name,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($user === null) {
            $payload['email'] = $email;
            $payload['created_at'] = date('Y-m-d H:i:s');
            $this->db->table('users')->insert($payload);

            return (int) $this->db->insertID();
        }

        $this->db->table('users')->where('id', $user['id'])->update($payload);

        return (int) $user['id'];
    }
}
