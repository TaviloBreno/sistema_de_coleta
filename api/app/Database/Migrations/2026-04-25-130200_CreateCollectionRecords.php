<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCollectionRecords extends Migration
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
            'collection_point_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'waste_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'quantity' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'collected_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey('collection_point_id');
        $this->forge->addKey('waste_type_id');
        $this->forge->addForeignKey('collection_point_id', 'collection_points', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('waste_type_id', 'waste_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('collection_records');
    }

    public function down()
    {
        $this->forge->dropTable('collection_records');
    }
}
