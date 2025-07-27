<?php
// includes/sidebar.php
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">KOPERASI</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item <?php echo (isset($current_page) && $current_page == 'dashboard') ? 'active' : ''; ?>">
        <a class="nav-link" href="index.php?page=dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Manajemen Data
    </div>

    <li class="nav-item <?php echo (isset($current_page) && in_array($current_page, ['customer', 'item', 'sales', 'transaction', 'petugas'])) ? 'active' : ''; ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseData"
            aria-expanded="true" aria-controls="collapseData">
            <i class="fas fa-fw fa-folder"></i>
            <span>Master Data</span>
        </a>
        <div id="collapseData" class="collapse <?php echo (isset($current_page) && in_array($current_page, ['customer', 'item', 'sales', 'transaction', 'petugas'])) ? 'show' : ''; ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pengelolaan Data:</h6>
                <a class="collapse-item <?php echo (isset($current_page) && $current_page == 'customer') ? 'active' : ''; ?>" href="index.php?page=customer">Customer</a>
                <a class="collapse-item <?php echo (isset($current_page) && $current_page == 'item') ? 'active' : ''; ?>" href="index.php?page=item">Item</a>
                <a class="collapse-item <?php echo (isset($current_page) && $current_page == 'sales') ? 'active' : ''; ?>" href="index.php?page=sales">Sales</a>
                <a class="collapse-item <?php echo (isset($current_page) && $current_page == 'transaction') ? 'active' : ''; ?>" href="index.php?page=transaction">Transaction</a>
                <a class="collapse-item <?php echo (isset($current_page) && $current_page == 'petugas') ? 'active' : ''; ?>" href="index.php?page=petugas">Petugas</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>