<?php
namespace App\Controllers;

use App\Models\CustomerModel;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class CustomerController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new CustomerModel();
        helper(['form', 'url']);
    }

    // List all customers
    public function index()
    {
        $data = [
            'title' => 'Manage Customers',
            'customers' => $this->model->paginate(10),
            'pager' => $this->model->pager
        ];
        return view('admin/customers/index', $data);
    }

    // Show create form
    public function create()
    {
        $data = ['title' => 'Add New Customer'];
        return view('admin/customers/create', $data);
    }

    // Store new customer
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'logo' => 'uploaded[logo]|max_size[logo,1024]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $logo = $this->request->getFile('logo');
        $newName = $logo->getRandomName();
        $logo->move(ROOTPATH . 'public/assets/uploads/logos', $newName);

        $this->model->save([
            'name' => $this->request->getPost('name'),
            'logo' => 'assets/uploads/logos/' . $newName
        ]);

        return redirect()
            ->to('/admin/customers')
            ->with('success', 'Customer created successfully');
    }

    // Show edit form
    public function edit($id)
    {
        $customer = $this->model->find($id);
        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Customer',
            'customer' => $customer
        ];
        return view('admin/customers/edit', $data);
    }

    // Update customer
    public function update($id)
    {
        $customer = $this->model->find($id);
        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'logo' => 'max_size[logo,1024]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = ['name' => $this->request->getPost('name')];

        // Handle logo update if new file uploaded
        if ($logo = $this->request->getFile('logo')) {
            if ($logo->isValid() && !$logo->hasMoved()) {
                // Delete old logo if exists
                if ($customer['logo'] && file_exists(ROOTPATH . 'public/' . $customer['logo'])) {
                    unlink(ROOTPATH . 'public/' . $customer['logo']);
                }

                $newName = $logo->getRandomName();
                $logo->move(ROOTPATH . 'public/assets/uploads/logos', $newName);
                $data['logo'] = 'assets/uploads/logos/' . $newName;
            }
        }

        $this->model->update($id, $data);

        return redirect()
            ->to('/admin/customers')
            ->with('success', 'Customer updated successfully');
    }

    // Delete customer
    public function delete($id)
    {
        $customer = $this->model->find($id);
        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Delete logo file if exists
        if ($customer['logo'] && file_exists(ROOTPATH . 'public/' . $customer['logo'])) {
            unlink(ROOTPATH . 'public/' . $customer['logo']);
        }

        $this->model->delete($id);

        return redirect()
            ->to('/admin/customers')
            ->with('success', 'Customer deleted successfully');
    }
}
