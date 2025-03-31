<?php
namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $allowedFields = ['customer_name', 'customer_logo', 'customer_pseudo_id'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'customer_name' => 'required|min_length[2]|max_length[100]',
        'customer_logo' => 'permit_empty'
    ];

    public function generatePseudoId(): string
    {
        return md5(uniqid(rand(), true));
    }
}
