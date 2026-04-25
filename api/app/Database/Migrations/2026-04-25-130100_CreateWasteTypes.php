<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWasteTypes extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'kg',
            ],
            'is_hazardous' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->addKey('name');
        $this->forge->createTable('waste_types');
    }

    public function down()
    {
        $this->forge->dropTable('waste_types');
    }
}
