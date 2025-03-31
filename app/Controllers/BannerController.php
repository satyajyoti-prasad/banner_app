<?php

/**
 * BannerController
 *
 * Developed By: Satyajyoti Prasad
 * Date of Creation: 2025-03-30
 * Language: CodeIgniter 4 (CI4)
 * Description: Handles banner operations like adding/updating for customers,Creating Dynamic JS for client.
 */

namespace App\Controllers;

use App\Models\BannerModel;
use App\Models\CustomerModel;

class BannerController extends BaseController
{
    protected $model;
    protected $customerModel;

    /**
     * Constructor to initialize models and check admin authentication.
     */
    public function __construct()
    {
        $this->model = new BannerModel();  // Initialize the Banner model
        $this->customerModel = new CustomerModel();  // Initialize the Customer model
        helper(['form', 'url']);  // Load form and URL helper
    }

    // Display the list of banners
    public function index()
    {
        $data = [
            'title' => 'Manage Banners',
            'banners' => $this
                ->model
                ->select('banners.*, customers.customer_name, customers.customer_pseudo_id')
                ->join('customers', 'customers.customer_id = banners.banner_customer_id')
                ->paginate(10),  // Paginate the results
            'pager' => $this->model->pager
        ];
        return view('admin/banners/index', $data);
    }

    // Show the create banner form
    public function create()
    {
        $data = [
            'title' => 'Add New Banner',
            'customers' => $this->customerModel->findAll()  // Fetch all customers
        ];
        return view('admin/banners/create', $data);
    }

    // Store a new banner in the database
    public function store()
    {
        // Validation rules for the banner form
        $rules = [
            'customer_id' => 'required|numeric',
            'image' => 'uploaded[image]|max_size[image,2048]|is_image[image]',
            'link_url' => 'required|valid_url',
            'alt_text' => 'required|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle the uploaded image
        $image = $this->request->getFile('image');
        $newName = $image->getRandomName();  // Generate a random filename
        $image->move(ROOTPATH . 'public/assets/uploads/banners', $newName);

        // Save banner data to database
        $this->model->save([
            'banner_customer_id' => $this->request->getPost('customer_id'),
            'banner_image_url' => 'assets/uploads/banners/' . $newName,
            'banner_link_url' => $this->request->getPost('link_url'),
            'banner_alt_text' => $this->request->getPost('alt_text'),
            'banner_is_active' => 1
        ]);

        return redirect()->to('/admin/banners')->with('success', 'Banner created successfully');
    }

    // Show the edit banner form
    public function edit($id)
    {
        $banner = $this->model->find($id);
        if (!$banner) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Banner',
            'banner' => $banner,
            'customers' => $this->customerModel->findAll()
        ];
        return view('admin/banners/edit', $data);
    }

    // Update an existing banner
    public function update($id)
    {
        $banner = $this->model->find($id);
        if (!$banner) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'customer_id' => 'required|numeric',
            'image' => 'max_size[image,2048]|is_image[image]',
            'link_url' => 'required|valid_url',
            'alt_text' => 'required|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'banner_customer_id' => $this->request->getPost('customer_id'),
            'banner_link_url' => $this->request->getPost('link_url'),
            'banner_alt_text' => $this->request->getPost('alt_text')
        ];

        // Handle image upload
        if ($image = $this->request->getFile('image')) {
            if ($image->isValid() && !$image->hasMoved()) {
                if ($banner['banner_image_url'] && file_exists(ROOTPATH . 'public/' . $banner['banner_image_url'])) {
                    unlink(ROOTPATH . 'public/' . $banner['banner_image_url']);
                }
                $newName = $image->getRandomName();
                $image->move(ROOTPATH . 'public/assets/uploads/banners', $newName);
                $data['banner_image_url'] = 'assets/uploads/banners/' . $newName;
            }
        }

        $this->model->update($id, $data);
        return redirect()->to('/admin/banners')->with('success', 'Banner updated successfully');
    }

    // Delete a banner
    public function delete($id)
    {
        $banner = $this->model->find($id);
        if (!$banner) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($banner['banner_image_url'] && file_exists(ROOTPATH . 'public/' . $banner['banner_image_url'])) {
            unlink(ROOTPATH . 'public/' . $banner['banner_image_url']);
        }

        $this->model->delete($id);
        return redirect()->to('/admin/banners')->with('success', 'Banner deleted successfully');
    }

    // Toggle the banner active/inactive status
    public function toggle($id)
    {
        $banner = $this->model->find($id);
        if (!$banner) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->model->update($id, [
            'banner_is_active' => $banner['banner_is_active'] ? 0 : 1
        ]);

        return redirect()->back()->with('success', 'Banner status updated');
    }

    // Generate dynamic JS for embedding

    public function generateJS($pseudoId)
    {
        $customer = $this->customerModel->where('customer_pseudo_id', $pseudoId)->first();

        if (!$customer) {
            header('Content-Type: application/javascript');
            echo "console.error('Customer not found');";
            return;
        }

        header('Content-Type: application/javascript');

        // Get optional parameters from query string with defaults
        $width = $this->request->getGet('width') ?? '100%';
        $height = $this->request->getGet('height') ?? 'auto';
        $position = $this->request->getGet('position') ?? 'bottom';
        $zIndex = $this->request->getGet('zIndex') ?? '9999';

        echo view('embedded_js/embedded_banner', [
            'pseudoId' => $pseudoId,
            'baseUrl' => base_url(),
            'width' => $width,
            'height' => $height,
            'position' => $position,
            'zIndex' => $zIndex,
            'hasBanner' => $this
                ->model
                ->where('banner_customer_id', $customer['customer_id'])
                ->where('banner_is_active', 1)
                ->countAllResults() > 0
        ]);
    }

    public function getBanner($pseudoId)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Max-Age: 86400');
        header('Content-Type: application/json');

        $customer = $this->customerModel->where('customer_pseudo_id', $pseudoId)->first();

        if (!$customer) {
            return $this->response->setJSON(['error' => 'Customer not found'])->setStatusCode(404);
        }

        $banner = $this
            ->model
            ->where('banner_customer_id', $customer['customer_id'])
            ->where('banner_is_active', 1)
            ->orderBy('banner_created_on', 'DESC')
            ->first();

        if (!$banner) {
            return $this->response->setJSON(['error' => 'No active banner found'])->setStatusCode(404);
        }

        // Include optional parameters in response
        // Optional parameters if there or add default
        $response = [
            'image_url' => base_url($banner['banner_image_url']),  // Full URL
            'link_url' => $banner['banner_link_url'],
            'alt_text' => $banner['banner_alt_text'],
            'width' => $this->request->getGet('width') ?? '100%',
            'height' => $this->request->getGet('height') ?? 'auto',
            'position' => $this->request->getGet('position') ?? 'bottom',
            'zIndex' => $this->request->getGet('zIndex') ?? '9999'
        ];

        return $this->response->setJSON($response);
    }
}
