<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCollectionEvents extends Migration
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
            'collection_record_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'event_type' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'default' => 'andamento',
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'event_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey('collection_record_id');
        $this->forge->addForeignKey('collection_record_id', 'collection_records', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('collection_events');
    }

    public function down()
    {
        $this->forge->dropTable('collection_events');
    }
}
