<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'au_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'au_username' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'au_password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'au_reset_token' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => true,
            ],
            'au_reset_expires' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'au_created_on' => [
                'type' => 'DATETIME',
                'default' => 'current_timestamp()',
            ],
        ]);

        $this->forge->addKey('au_id', true);
        $this->forge->createTable('admin_users');

        // Add initial admin user
        $data = [
            'au_username' => 'user@admin.com',
            'au_password' => '$2y$12$Ggi5smTanGia3hz5b/ls4ejezXvRbv2lKCr12ACx9CEcA5xNlt85y'
        ];
        $this->db->table('admin_users')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('admin_users');
    }
}
