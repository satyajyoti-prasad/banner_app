<?= $this->extend('admin/components/layout') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><?= $title ?></h1>
        <a href="/admin/customers/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Customer
        </a>
    </div>

   

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pseudo ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($customers as $customer): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if ($customer['customer_logo']): ?>
                            <img src="<?= base_url($customer['customer_logo']) ?>" alt="<?= esc($customer['customer_name']) ?>" class="h-10 w-10 rounded-full object-cover">
                        <?php else: ?>
                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500 text-sm"><?= strtoupper(substr($customer['customer_name'], 0, 1)) ?></span>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?= esc($customer['customer_name']) ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 font-mono"><?= $customer['customer_pseudo_id'] ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="/admin/customers/edit/<?= $customer['customer_id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <a href="/admin/customers/delete/<?= $customer['customer_id'] ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
            <div class="inline-flex rounded-md shadow-sm">
                <?= $pager->links() ?>
            </div>
        </div>
    </div>
</div>

<style>
   .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .pagination li a {
        display: block;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .pagination li a:hover {
        background-color: #f3f4f6;
    }
    
    .pagination .active a {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }
    
    .pagination .disabled a {
        color: #9ca3af;
        cursor: not-allowed;
    }
    
   
</style>

<?= $this->endSection() ?>