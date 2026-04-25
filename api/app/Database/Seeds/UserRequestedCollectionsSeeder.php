<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserRequestedCollectionsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        
        // Find or create a default company
        $company = $this->db->table('companies')->limit(1)->get()->getRowArray();
        $companyId = $company ? $company['id'] : 1;

        $streets = [
            'Manoel Idelfonso' => 'Rua Manoel Idelfonso - Crateus, CE',
            'Sargento Herminio' => 'Avenida Sargento Herminio - Crateus, CE',
            'Moura Fe' => 'Rua Moura Fé - Crateus, CE',
            'Dom Pedro II' => 'Rua Dom Pedro Segundo - Crateus, CE',
        ];

        foreach ($streets as $streetName => $address) {
            $routeName = "Rota " . $streetName;
            
            // Create the route
            $this->db->table('collection_routes')->insert([
                'company_id' => $companyId,
                'name' => $routeName,
                'start_point' => 'Galpao Crateus',
                'end_point' => $streetName,
                'scheduled_at' => $now,
                'status' => 'pendente',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $routeId = $this->db->insertID();

            // Create the point for this route
            $this->db->table('collection_points')->insert([
                'collection_route_id' => $routeId,
                'name' => 'Ponto ' . $streetName,
                'address' => $address,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
