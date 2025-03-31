<?= $this->extend('admin/components/layout') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <!-- Page Header with Add Banner Button -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><?= $title ?></h1>
        <a href="/admin/banners/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Banner
        </a>
    </div>

   

    <!-- Banners Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Banner Preview</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pseudo ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link URL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="bannersTable">
                <?php foreach ($banners as $index => $banner): ?>
                <tr data-pseudo-id="<?= $banner['customer_pseudo_id'] ?>" 
                    class="cursor-pointer hover:bg-gray-50 <?= $index === 0 ? 'bg-blue-50' : '' ?>" 
                    onclick="selectBanner(this, '<?= $banner['customer_pseudo_id'] ?>')">
                    
                    <!-- Banner Preview Image -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="<?= base_url($banner['banner_image_url']) ?>" 
                             alt="<?= esc($banner['banner_alt_text']) ?>" 
                             class="h-16 w-32 object-cover rounded cursor-pointer" 
                             onclick="event.stopPropagation(); showBannerPreview('<?= base_url($banner['banner_image_url']) ?>', '<?= esc($banner['banner_alt_text']) ?>')">
                    </td>
                    
                    <!-- Customer Name -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?= esc($banner['customer_name']) ?></div>
                    </td>
                    
                    <!-- Pseudo ID -->
                    <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-500">
                        <?= $banner['customer_pseudo_id'] ?>
                    </td>
                    
                    <!-- Link URL -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-blue-600 truncate max-w-xs"><?= esc($banner['banner_link_url']) ?></div>
                    </td>
                    
                    <!-- Status Badge -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full <?= $banner['banner_is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $banner['banner_is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    
                    <!-- Action Buttons -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="/admin/banners/edit/<?= $banner['banner_id'] ?>" 
                           class="text-blue-600 hover:text-blue-900 mr-3" 
                           onclick="event.stopPropagation()">Edit</a>
                        
                        <a href="/admin/banners/toggle/<?= $banner['banner_id'] ?>" 
                           class="text-yellow-600 hover:text-yellow-900 mr-3" 
                           onclick="event.stopPropagation()">
                            <?= $banner['banner_is_active'] ? 'Deactivate' : 'Activate' ?>
                        </a>
                        
                        <a href="/admin/banners/delete/<?= $banner['banner_id'] ?>" 
                           class="text-red-600 hover:text-red-900" 
                           onclick="event.stopPropagation(); return confirm('Are you sure? This will permanently delete the banner.')">
                            Delete
                        </a>
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

    <!-- Banner Preview Modal -->
    <div id="bannerPreviewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="relative bg-white rounded-lg shadow-xl max-w-[90vw] max-h-[90vh]">
            <button onclick="closePreview()" class="absolute -top-3 -right-3 z-10 bg-gray-800 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-gray-700 transition-colors">
                <i class="fas fa-times"></i>
            </button>
            <div class="p-1 h-full">
                <img id="previewImage" src="" alt="" class="max-w-[85vw] max-h-[85vh] w-auto h-auto object-contain rounded-lg transition-opacity duration-200">
            </div>
        </div>
    </div>

    <!-- Embed Instructions Section -->
    <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Embed Instructions</h2>
            <button onclick="copyEmbedCode()" class="flex items-center text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                <i class="fas fa-copy mr-1"></i> Copy Code
            </button>
        </div>
        <div class="p-6">
            <p class="mb-4 text-gray-700">Add this code snippet to your client's web page to display the banner:</p>
            
            <div class="mb-6 bg-gray-800 rounded-lg overflow-hidden">
                <div class="flex justify-between items-center bg-gray-700 px-4 py-2">
                    <span class="text-xs font-mono text-gray-300">JavaScript Embed Code</span>
                    <span id="copyStatus" class="text-xs text-green-400 hidden">Copied!</span>
                </div>
                <div id="embedCode" class="p-4 overflow-x-auto text-gray-100 font-mono text-sm">
                    <?php $defaultPseudoId = !empty($banners) ? $banners[0]['customer_pseudo_id'] : 'PSEUDO_ID'; ?>
                    
                    <span class="text-purple-400">&lt;script&gt;</span><br>
                    &nbsp;&nbsp;window.bannerConfig = {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;position: <span class="text-green-400">'bottom'</span>,<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;width: <span class="text-green-400">'100%'</span>,<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;height: <span class="text-green-400">'auto'</span><br>
                    &nbsp;&nbsp;};<br>
                    <span class="text-purple-400">&lt;/script&gt;</span><br><br>
                    
                    <span class="text-purple-400">&lt;script</span> async src="<?= base_url('banner.js/') . $defaultPseudoId ?> "<span class="text-purple-400">&gt;&lt;/script&gt;</span>
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
                        <h3 class="text-sm font-medium text-blue-800">Implementation Tips</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Place this code just before the closing <code class="bg-blue-100 px-1 rounded">&lt;/body&gt;</code> tag</li>
                                <li>For best performance, load asynchronously by adding <code class="bg-blue-100 px-1 rounded">async</code> attribute</li>
                                <li>Refresh your page after implementation to see changes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showBannerPreview(imageUrl, altText) {
        const modal = document.getElementById('bannerPreviewModal');
        const previewImage = document.getElementById('previewImage');
        
        previewImage.classList.add('opacity-0');
        previewImage.src = imageUrl;
        previewImage.alt = altText;
        
        modal.classList.remove('pointer-events-none');
        modal.classList.remove('opacity-0');
        document.body.classList.add('overflow-hidden');
        
        previewImage.onload = function() {
            setTimeout(() => {
                previewImage.classList.remove('opacity-0');
            }, 10);
        };
    }

    function closePreview() {
        const modal = document.getElementById('bannerPreviewModal');
        const previewImage = document.getElementById('previewImage');
        
        modal.classList.add('opacity-0');
        modal.classList.add('pointer-events-none');
        document.body.classList.remove('overflow-hidden');
        
        previewImage.src = '';
        previewImage.alt = '';
    }

    function selectBanner(row, pseudoId) {
        document.querySelectorAll('#bannersTable tr').forEach(r => {
            r.classList.remove('bg-blue-50');
        });
        
        row.classList.add('bg-blue-50');
        
        const embedCodeDiv = document.getElementById('embedCode');
        embedCodeDiv.innerHTML = `
            <span class="text-purple-400">&lt;script&gt;</span><br>
            &nbsp;&nbsp;window.bannerConfig={<br>
            &nbsp;&nbsp;&nbsp;&nbsp;position:<span class="text-green-400">'top'</span>,<br>
            &nbsp;&nbsp;&nbsp;&nbsp;width:<span class="text-green-400">'100%'</span>,<br>
            &nbsp;&nbsp;&nbsp;&nbsp;height:<span class="text-green-400">'120px'</span><br>
            &nbsp;&nbsp;};<br>
            <span class="text-purple-400">&lt;/script&gt;</span><br><br>
            <span class="text-purple-400">&lt;script</span>  async src="<?= base_url('banner.js/') ?><span class="text-yellow-400 font-bold">${pseudoId} </span>"<span class="text-purple-400">&gt;&lt;/script&gt;</span>
        `;



    }

    function copyEmbedCode() {
        const pseudoId = document.querySelector('#bannersTable tr.bg-blue-50')?.getAttribute('data-pseudo-id') || '<?= !empty($banners) ? $banners[0]['customer_pseudo_id'] : 'PSEUDO_ID' ?>';

        const code = `<script>
        window.bannerConfig={
            position:'top',
            width:'100%',
            height:'200px'
        };
        <\/script>
        <script async src="<?= base_url('banner.js/') ?><span class="text-yellow-400 font-bold">${pseudoId}</span>"><\/script>`;

        
        navigator.clipboard.writeText(code).then(() => {
            showCopySuccess();
        }).catch(err => {
            console.error('Failed to copy: ', err);
            fallbackCopyText(code);
        });
    }

    function showCopySuccess() {
        const status = document.getElementById('copyStatus');
        status.classList.remove('hidden');
        setTimeout(() => status.classList.add('hidden'), 2000);
    }

    function fallbackCopyText(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            showCopySuccess();
        } catch (err) {
            console.error('Fallback copy failed: ', err);
        }
        document.body.removeChild(textarea);
    }

    document.getElementById('bannerPreviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePreview();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePreview();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const firstRow = document.querySelector('#bannersTable tr');
        if (firstRow) {
            const pseudoId = firstRow.getAttribute('data-pseudo-id');
            selectBanner(firstRow, pseudoId);
        }else{


        }
    });
</script>

<style>
    #bannersTable tr {
        transition: background-color 0.2s ease;
    }
    
    #bannersTable tr.bg-blue-50 {
        background-color: #eff6ff;
    }
    
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
    
    #bannerPreviewModal {
        transition: opacity 0.3s ease;
    }
</style>
<?= $this->endSection() ?>