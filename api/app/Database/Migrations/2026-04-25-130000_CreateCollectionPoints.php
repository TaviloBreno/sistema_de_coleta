<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCollectionPoints extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'collection_route_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => 170,
            ],
            'contact_name' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => true,
            ],
            'contact_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'scheduled_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('collection_route_id');
        $this->forge->addForeignKey('collection_route_id', 'collection_routes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('collection_points');
    }

    public function down()
    {
        $this->forge->dropTable('collection_points');
    }
}
