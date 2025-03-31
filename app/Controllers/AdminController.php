<?php

/**
 * AdminController
 *
 * Developer: Satyajyoti Prasad
 * Date of Creation: 2025-03-30
 * Language: CodeIgniter 4 (CI4)
 * Description: Handles admin dashboard operations including statistics on customers and banners.
 */

namespace App\Controllers;

use App\Models\BannerModel;
use App\Models\CustomerModel;

class AdminController extends BaseController
{
    protected $bannerModel;
    protected $customerModel;

    /**
     * Constructor to initialize models and check admin authentication.
     */
    public function __construct()
    {
        $this->bannerModel = new BannerModel();
        $this->customerModel = new CustomerModel();

        // Ensure only logged-in admins can access this controller
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/login')->send();
        }
    }

    /**
     * Admin Dashboard
     *
     * Displays overall statistics, including customer and banner counts,
     * active banners, and recent records.
     */
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard',
            'customerCount' => $this->customerModel->countAll(),  // Total customers
            'bannerCount' => $this->bannerModel->countAll(),  // Total banners
            'activeBannerCount' => $this->bannerModel->where('banner_is_active', 1)->countAllResults(),  // Active banners
            'recentCustomers' => $this->customerModel->orderBy('customer_created_at', 'DESC')->findAll(5),  // Last 5 customers
            'recentBanners' => $this->bannerModel->orderBy('banner_created_on', 'DESC')->findAll(5)  // Last 5 banners
        ];

        return view('admin/components/dashboard', $data);
    }
}
