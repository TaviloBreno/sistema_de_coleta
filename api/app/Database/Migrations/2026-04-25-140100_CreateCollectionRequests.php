<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCollectionRequests extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'collection_route_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'collection_point_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'scheduled_at' => [
                'type' => 'DATETIME',
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'pendente',
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
        $this->forge->addKey('user_id');
        $this->forge->addKey('collection_route_id');
        $this->forge->addKey('collection_point_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('collection_route_id', 'collection_routes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('collection_point_id', 'collection_points', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('collection_requests');
    }

    public function down()
    {
        $this->forge->dropTable('collection_requests');
    }
}
