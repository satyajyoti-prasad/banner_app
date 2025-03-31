<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Banner Admin' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white">
        <div class="flex items-center justify-center h-16 px-4 border-b border-gray-700">
            <h1 class="text-xl font-bold">Banner Admin</h1>
        </div>
        <nav class="p-4">
            <a href="/admin" class="flex items-center px-4 py-3 rounded-lg <?= current_url() == site_url('admin') ? 'bg-gray-700' : 'hover:bg-blue-700' ?>">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="/admin/customers" class="flex items-center px-4 py-3 rounded-lg <?= strpos(current_url(), 'customers') !== false ? 'bg-gray-700' : 'hover:bg-blue-700' ?>">
                <i class="fas fa-users mr-3"></i>
                Customers
            </a>
            <a href="/admin/banners" class="flex items-center px-4 py-3 rounded-lg <?= strpos(current_url(), 'banners') !== false ? 'bg-gray-700' : 'hover:bg-blue-700' ?>">
                <i class="fas fa-image mr-3"></i>
                Banners
            </a>
            <a href="/logout" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 text-red-500">
                <i class="fas fa-sign-out-alt mr-3"></i>
                Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm">
            <div class="flex justify-between items-center px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800"><?= $title ?? 'Dashboard' ?></h2>
                <div class="flex items-center">
                    <span class="text-sm text-gray-600 mr-2">Welcome,</span>
                    <span class="font-medium"><?= session()->get('admin_username') ?></span>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
            <?php if (session()->getFlashdata('success')): ?>
                <div id="flash-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div id="flash-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script>
        function hideFlashMessages() {
            setTimeout(() => {
                document.getElementById('flash-success')?.remove();
                document.getElementById('flash-error')?.remove();
            }, 10000); // Hiding in 10 seconds
        }

        document.addEventListener("DOMContentLoaded", hideFlashMessages);
    </script>
</body>
</html>
