<?php

/**
 * CustomerController
 *
 * Developed By: Satyajyoti Prasad
 * Date of Creation: 2025-03-30
 * Language: CodeIgniter 4 (CI4)
 * Description: Handles customer CRUD.
 */

namespace App\Controllers;

use App\Models\CustomerModel;

class CustomerController extends BaseController
{
    protected $model;

    /**
     * Constructor to initialize models and check admin authentication.
     */
    public function __construct()
    {
        $this->model = new CustomerModel();
        helper(['form', 'url']);
    }

    // Display list of customers with pagination
    public function index()
    {
        $data = [
            'title' => 'Manage Customers',
            'customers' => $this->model->paginate(10),
            'pager' => $this->model->pager
        ];
        return view('admin/customers/index', $data);
    }

    // Show customer creation form
    public function create()
    {
        $data = [
            'title' => 'Add New Customer',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/customers/create', $data);
    }

    // Store a newly created customer in the database
    public function store()
    {
        $rules = [
            'customer_name' => 'required|min_length[3]|max_length[100]',
            'customer_logo' => [
                'rules' => 'uploaded[customer_logo]|max_size[customer_logo,1024]|is_image[customer_logo]|mime_in[customer_logo,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'The logo file size should not exceed 1MB',
                    'is_image' => 'Only image files are allowed',
                    'mime_in' => 'Only JPG, JPEG, and PNG images are allowed'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $logo = $this->request->getFile('customer_logo');
        if ($logo->isValid() && !$logo->hasMoved()) {
            $newName = $logo->getRandomName();
            $logo->move(ROOTPATH . 'public/assets/uploads/logos', $newName);

            // Save customer data
            $this->model->save([
                'customer_name' => $this->request->getPost('customer_name'),
                'customer_logo' => 'assets/uploads/logos/' . $newName,
                'customer_pseudo_id' => $this->model->generatePseudoId()
            ]);
        }

        return redirect()->to('/admin/customers')->with('success', 'Customer created successfully');
    }

    // Show customer edit form
    public function edit($id)
    {
        $customer = $this->model->find($id);
        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Customer',
            'customer' => $customer,
            'validation' => \Config\Services::validation()
        ];
        return view('admin/customers/edit', $data);
    }

    // Update existing customer information
    public function update($id)
    {
        $customer = $this->model->find($id);
        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'customer_name' => 'required|min_length[3]|max_length[100]',
            'customer_logo' => [
                'rules' => 'max_size[customer_logo,1024]|is_image[customer_logo]|mime_in[customer_logo,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'The logo file size should not exceed 1MB',
                    'is_image' => 'Only image files are allowed',
                    'mime_in' => 'Only JPG, JPEG, and PNG images are allowed'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = ['customer_name' => $this->request->getPost('customer_name')];

        // Handle logo update if new file uploaded
        $logo = $this->request->getFile('customer_logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            // Delete old logo if it exists
            if ($customer['customer_logo'] && file_exists(ROOTPATH . 'public/' . $customer['customer_logo'])) {
                unlink(ROOTPATH . 'public/' . $customer['customer_logo']);
            }

            $newName = $logo->getRandomName();
            $logo->move(ROOTPATH . 'public/assets/uploads/logos', $newName);
            $data['customer_logo'] = 'assets/uploads/logos/' . $newName;
        }

        $this->model->update($id, $data);

        return redirect()->to('/admin/customers')->with('success', 'Customer updated successfully');
    }

    // Delete customer and associated logo file
    public function delete($id)
    {
        $customer = $this->model->find($id);
        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Delete logo file if exists
        if ($customer['customer_logo'] && file_exists(ROOTPATH . 'public/' . $customer['customer_logo'])) {
            unlink(ROOTPATH . 'public/' . $customer['customer_logo']);
        }

        $this->model->delete($id);

        return redirect()->to('/admin/customers')->with('success', 'Customer deleted successfully');
    }
}
