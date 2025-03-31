<?= $this->extend('admin/components/layout') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6"><?= $title ?></h1>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/admin/banners/update/<?= $banner['banner_id'] ?>" method="post" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
            <?= csrf_field() ?>
            
            <div class="mb-4">
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                <select name="customer_id" id="customer_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <?php foreach ($customers as $customer): ?>
                    <option value="<?= $customer['customer_id'] ?>" <?= $customer['customer_id'] == $banner['banner_customer_id'] ? 'selected' : '' ?>>
                        <?= esc($customer['customer_name']) ?> (<?= $customer['customer_pseudo_id'] ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Banner</label>
                <img id="current-banner" src="<?= base_url($banner['banner_image_url']) ?>" alt="<?= esc($banner['banner_alt_text']) ?>" class="h-32 w-full object-cover rounded-md mb-2">
                
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Replace Image</label>
                <input type="file" name="image" id="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-xs text-gray-500">Leave blank to keep current image</p>
                
                <!-- Preview New Image -->
                <div id="image-preview-container" class="mt-2 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preview</label>
                    <img id="image-preview" class="h-32 w-full object-cover rounded-md">
                </div>
            </div>
            
            <div class="mb-4">
                <label for="link_url" class="block text-sm font-medium text-gray-700 mb-1">Destination URL</label>
                <input type="url" name="link_url" id="link_url" value="<?= esc($banner['banner_link_url']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            
            <div class="mb-4">
                <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-1">Alt Text</label>
                <input type="text" name="alt_text" id="alt_text" value="<?= esc($banner['banner_alt_text']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            
            <div class="flex justify-end">
                <a href="/admin/banners" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Banner
                </button>
            </div>
        </form>
    </div>
</div>

<div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Special Instruction</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>On changing an existing banner, the new image will be uploaded and the old one will be deleted.</li>
                                <li>It will take around 1 - 2 minutes for the new banner to be visible on the website.</li>
                                <li>Refresh is not needed to see the changes.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

<script>
    document.getElementById("image").addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("image-preview").src = e.target.result;
                document.getElementById("image-preview-container").classList.remove("hidden");
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById("image-preview-container").classList.add("hidden");
        }
    });
</script>

<?= $this->endSection() ?>
