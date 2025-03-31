<?php
namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admin_users';
    protected $primaryKey = 'au_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['au_username', 'au_password', 'au_reset_token', 'au_reset_expires'];
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected $validationRules = [
        'au_username' => 'required|min_length[3]|max_length[50]|is_unique[admin_users.au_username]',
        'au_password' => 'required|min_length[12]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/]'
    ];

    protected $validationMessages = [
        'au_username' => [
            'is_unique' => 'This username is already taken.'
        ]
    ];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['au_password'])) {
            $data['data']['au_password'] = password_hash($data['data']['au_password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function verifyCredentials(string $username, string $password): ?array
    {
        $admin = $this->where('au_username', $username)->first();
        if (!$admin || !password_verify($password, $admin['au_password'])) {
            return null;
        }
        return $admin;
    }
}
