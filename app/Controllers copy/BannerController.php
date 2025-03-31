<?php
namespace App\Controllers;

use App\Models\BannerModel;
use App\Models\CustomerModel;

class BannerController extends BaseController
{
    protected $model;
    protected $customerModel;

    public function __construct()
    {
        $this->model = new BannerModel();
        $this->customerModel = new CustomerModel();
        helper(['form', 'url']);
    }

    // List all banners
    public function index()
    {
        $data = [
            'title' => 'Manage Banners',
            'banners' => $this
                ->model
                ->select('banners.*, customers.name as customer_name, customers.pseudo_id')
                ->join('customers', 'customers.id = banners.customer_id')
                ->paginate(10),
            'pager' => $this->model->pager
        ];
        return view('admin/banners/index', $data);
    }

    // Show create form
    public function create()
    {
        $data = [
            'title' => 'Add New Banner',
            'customers' => $this->customerModel->findAll()
        ];
        return view('admin/banners/create', $data);
    }

    // Store new banner
    public function store()
    {
        $rules = [
            'customer_id' => 'required|numeric',
            'image' => 'uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]',
            'link_url' => 'required|valid_url',
            'alt_text' => 'required|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $image = $this->request->getFile('image');
        $newName = $image->getRandomName();
        $image->move(ROOTPATH . 'public/assets/uploads/banners', $newName);

        $this->model->save([
            'customer_id' => $this->request->getPost('customer_id'),
            'image_url' => 'assets/uploads/banners/' . $newName,
            'link_url' => $this->request->getPost('link_url'),
            'alt_text' => $this->request->getPost('alt_text'),
            'is_active' => 1
        ]);

        return redirect()
            ->to('/admin/banners')
            ->with('success', 'Banner created successfully');
    }

    // Show edit form
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

    // Update banner
    public function update($id)
    {
        $banner = $this->model->find($id);
        if (!$banner) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'customer_id' => 'required|numeric',
            'image' => 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]',
            'link_url' => 'required|valid_url',
            'alt_text' => 'required|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'customer_id' => $this->request->getPost('customer_id'),
            'link_url' => $this->request->getPost('link_url'),
            'alt_text' => $this->request->getPost('alt_text')
        ];

        // Handle image update if new file uploaded
        if ($image = $this->request->getFile('image')) {
            if ($image->isValid() && !$image->hasMoved()) {
                // Delete old image if exists
                if ($banner['image_url'] && file_exists(ROOTPATH . 'public/' . $banner['image_url'])) {
                    unlink(ROOTPATH . 'public/' . $banner['image_url']);
                }

                $newName = $image->getRandomName();
                $image->move(ROOTPATH . 'public/assets/uploads/banners', $newName);
                $data['image_url'] = 'assets/uploads/banners/' . $newName;
            }
        }

        $this->model->update($id, $data);

        return redirect()
            ->to('/admin/banners')
            ->with('success', 'Banner updated successfully');
    }

    // Delete banner
    public function delete($id)
    {
        $banner = $this->model->find($id);
        if (!$banner) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Delete image file if exists
        if ($banner['image_url'] && file_exists(ROOTPATH . 'public/' . $banner['image_url'])) {
            unlink(ROOTPATH . 'public/' . $banner['image_url']);
        }

        $this->model->delete($id);

        return redirect()
            ->to('/admin/banners')
            ->with('success', 'Banner deleted successfully');
    }

    // Toggle banner status (active/inactive)
    public function toggle($id)
    {
        $banner = $this->model->find($id);
        if (!$banner) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->model->update($id, [
            'is_active' => $banner['is_active'] ? 0 : 1
        ]);

        return redirect()
            ->back()
            ->with('success', 'Banner status updated');
    }

    // Generate dynamic JS for embedding
    public function generateJS($pseudoId)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Max-Age: 86400');
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
            'zIndex' => $zIndex
        ]);
    }

    // API endpoint to get banner data with optional parameters
    public function getBanner($pseudoId)
    {
        // Enable CORS for the API endpoint
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Max-Age: 86400');
        header('Content-Type: application/json');

        $customer = $this->customerModel->where('pseudo_id', $pseudoId)->first();

        if (!$customer) {
            return $this->response->setJSON(['error' => 'Customer not found']);
        }

        $banner = $this
            ->model
            ->where('customer_id', $customer['id'])
            ->where('is_active', 1)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$banner) {
            return $this->response->setJSON(['error' => 'No active banner found']);
        }

        // Include optional parameters in response
        $response = [
            'image_url' => $banner['image_url'],
            'link_url' => $banner['link_url'],
            'alt_text' => $banner['alt_text'],
            // Optional parameters from query string
            'width' => $this->request->getGet('width') ?? '100%',
            'height' => $this->request->getGet('height') ?? 'auto',
            'position' => $this->request->getGet('position') ?? 'bottom',
            'zIndex' => $this->request->getGet('zIndex') ?? '9999'
        ];

        return $this->response->setJSON($response);
    }
}
