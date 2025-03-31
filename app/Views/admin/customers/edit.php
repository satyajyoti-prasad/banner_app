<?= $this->extend('admin/components/layout') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6"><?= $title ?></h1>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/admin/customers/update/<?= $customer['customer_id'] ?>" method="post" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
            <?= csrf_field() ?>
            
            <div class="mb-4">
                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                <input type="text" name="customer_name" id="customer_name" value="<?= esc($customer['customer_name']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            
            <div class="mb-4">
                <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                <div class="mt-1 flex items-center">
                    <?php if ($customer['customer_logo']): ?>
                        <img src="<?= base_url($customer['customer_logo']) ?>" alt="<?= esc($customer['customer_name']) ?>" class="h-12 w-12 rounded-full object-cover mr-4">
                    <?php else: ?>
                        <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100 mr-4">
                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </span>
                    <?php endif; ?>
                    <input type="file" name="customer_logo" id="customer_logo" class="focus:outline-none">
                </div>
                <p class="mt-1 text-xs text-gray-500">PNG, JPG up to 1MB</p>
            </div>
            
            <div class="flex justify-end">
                <a href="/admin/customers" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Customer
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>