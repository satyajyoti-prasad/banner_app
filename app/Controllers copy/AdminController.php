<?php
namespace App\Controllers;

use App\Models\BannerModel;
use App\Models\CustomerModel;

class AdminController extends BaseController
{
    protected $bannerModel;
    protected $customerModel;

    public function __construct()
    {
        $this->bannerModel = new BannerModel();
        $this->customerModel = new CustomerModel();

        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/login');
        }
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard',
            'customerCount' => $this->customerModel->countAll(),
            'bannerCount' => $this->bannerModel->countAll(),
            'activeBannerCount' => $this->bannerModel->where('is_active', 1)->countAllResults(),
            'recentCustomers' => $this->customerModel->orderBy('created_at', 'DESC')->findAll(5),
            'recentBanners' => $this->bannerModel->orderBy('created_at', 'DESC')->findAll(5)
        ];

        return view('admin/dashboard', $data);
    }
}
