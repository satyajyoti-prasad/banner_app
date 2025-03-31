<?php
namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'banner_id';
    protected $allowedFields = ['banner_customer_id', 'banner_image_url', 'banner_link_url', 'banner_alt_text', 'banner_is_active'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'banner_customer_id' => 'required|numeric',
        'banner_image_url' => 'required',
        'banner_link_url' => 'required|valid_url',
        'banner_alt_text' => 'required|max_length[100]'
    ];
}
