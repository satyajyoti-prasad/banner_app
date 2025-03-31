<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'customer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'customer_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'customer_logo' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'customer_pseudo_id' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
                'comment' => 'This to be shared along with script for dynamic banner',
            ],
            'customer_created_at' => [
                'type' => 'DATETIME',
                'default' => 'current_timestamp()',
            ],
        ]);

        $this->forge->addKey('customer_id', true);
        $this->forge->createTable('customers');
    }

    public function down()
    {
        $this->forge->dropTable('customers');
    }
}
