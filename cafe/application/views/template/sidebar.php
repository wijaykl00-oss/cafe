<!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url('dashboard'); ?>">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-coffee"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Cafe POS</div>
            </a>

            <hr class="sidebar-divider my-0">

            <!-- Dashboard -->
            <li class="nav-item <?php echo ($this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('dashboard'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Transaksi</div>

            <!-- Transaksi -->
            <li class="nav-item <?php echo ($this->uri->segment(1) == 'transaksi' && $this->uri->segment(2) == 'buat') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('transaksi/buat'); ?>">
                    <i class="fas fa-fw fa-cash-register"></i>
                    <span>Transaksi</span>
                </a>
            </li>

            <!-- Riwayat Transaksi -->
            <li class="nav-item <?php echo ($this->uri->segment(1) == 'transaksi' && $this->uri->segment(2) != 'buat') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('transaksi'); ?>">
                    <i class="fas fa-fw fa-receipt"></i>
                    <span>Riwayat Transaksi</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Master Data</div>

            <!-- Menu -->
            <li class="nav-item <?php echo ($this->uri->segment(1) == 'menu') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('menu'); ?>">
                    <i class="fas fa-fw fa-utensils"></i>
                    <span>Menu</span>
                </a>
            </li>

            <?php if ($this->session->userdata('role') == 'admin'): ?>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Laporan</div>

            <!-- Laporan -->
            <li class="nav-item <?php echo ($this->uri->segment(1) == 'laporan') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('laporan'); ?>">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Laporan Penjualan</span>
                </a>
            </li>
            <?php endif; ?>

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
         <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $this->session->userdata('nama') ?? 'User'; ?>
                                    <span class="badge badge-<?php echo $this->session->userdata('role') == 'admin' ? 'danger' : 'success'; ?> ml-1">
                                        <?php echo ucfirst($this->session->userdata('role') ?? 'kasir'); ?>
                                    </span>
                                </span>
                                <img class="img-profile rounded-circle" src="<?php echo base_url('assets/img/undraw_profile.svg'); ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    <?php echo $this->session->userdata('nama'); ?>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->