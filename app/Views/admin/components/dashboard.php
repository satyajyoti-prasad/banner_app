<?= $this->extend('admin/components/layout') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Overview</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Customers</p>
                    <p class="text-2xl font-semibold text-gray-800"><?= $customerCount ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Banners</p>
                    <p class="text-2xl font-semibold text-gray-800"><?= $bannerCount ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Banners</p>
                    <p class="text-2xl font-semibold text-gray-800"><?= $activeBannerCount ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Recent Customers</h2>
            </div>
            <div class="divide-y divide-gray-200">
                <?php foreach ($recentCustomers as $customer): ?>
                <div class="px-6 py-4 flex items-center">
                    <?php if ($customer['customer_logo']): ?>
                    <img src="<?= base_url($customer['customer_logo']) ?>" alt="<?= esc($customer['customer_name']) ?>" class="w-10 h-10 rounded-full mr-4">
                    <?php else: ?>
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mr-4">
                        <?= strtoupper(substr($customer['customer_name'], 0, 1)) ?>
                    </div>
                    <?php endif; ?>
                    <div>
                        <p class="font-medium text-gray-800"><?= esc($customer['customer_name']) ?></p>
                        <p class="text-sm text-gray-500 font-mono"><?= $customer['customer_pseudo_id'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Recent Banners</h2>
            </div>
            <div class="divide-y divide-gray-200">
                <?php foreach ($recentBanners as $banner): ?>
                <div class="px-6 py-4">
                    <div class="flex items-center mb-2">
                        <img src="<?= base_url($banner['banner_image_url']) ?>" alt="Banner Preview" class="w-16 h-10 object-cover rounded mr-3">
                        <div>
                            <p class="font-medium text-gray-800"><?= esc($banner['banner_alt_text']) ?></p>
                            <p class="text-sm text-gray-500"><?= esc($banner['banner_link_url']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>