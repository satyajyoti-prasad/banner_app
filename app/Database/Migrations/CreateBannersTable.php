<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBannersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'banner_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'banner_customer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'banner_image_url' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'banner_link_url' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'banner_alt_text' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'banner_is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'banner_created_on' => [
                'type' => 'DATETIME',
                'default' => 'current_timestamp()',
            ],
        ]);

        $this->forge->addKey('banner_id', true);
        $this->forge->createTable('banners');
    }

    public function down()
    {
        $this->forge->dropTable('banners');
    }
}
