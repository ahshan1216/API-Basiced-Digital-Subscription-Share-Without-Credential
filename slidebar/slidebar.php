<?

include '../identity.php';

?>
<aside class="main-sidebar sidebar-navy-primary bg-navy elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->
    <a href="../admin/" class="brand-link bg-primary text-sm">
        <img src="../logo.webp" alt="Store Logo" class="brand-image img-circle elevation-3 border-1"
            style="opacity: .8;width: 2.5rem;height: 2.5rem;max-height: unset">
        <span class="brand-text font-weight-light"><?php echo $websiteName; ?></span>
    </a>
    <!-- Sidebar -->
    <div
        class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
        <div class="os-resize-observer-host observed">
            <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
        </div>
        <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
            <div class="os-resize-observer"></div>
        </div>
        <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
        <div class="os-padding">
            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
                    <!-- Sidebar user panel (optional) -->
                    <div class="clearfix"></div>
                    <!-- Sidebar Menu -->
                    <nav class="mt-4">
                        <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child"
                            data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item dropdown">
                                <a href="../admin/" class="nav-link nav-home">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="../create_product/" class="nav-link nav-budget">
                                    <i class="nav-icon fas fa-wallet"></i>
                                    <p>
                                        Create Cookies
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="../create_user/" class="nav-link nav-budget">
                                    <i class="nav-icon fas fa-wallet"></i>
                                    <p>
                                        Create Users
                                    </p>
                                </a>
                            </li>


                            <li class="nav-header">Extension Download</li>
                            <li class="nav-item dropdown">
                                <a href="../extension/" target="_blank" class="nav-link nav-reports-budget">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>Download</p>
                                </a>
                            </li>


                            <li class="nav-header">System</li>
                            <li class="nav-item dropdown">
                                <a href="../logout/" class="nav-link nav-maintenance/category">
                                    <i class="nav-icon fas fa-th-list"></i>
                                    <p>
                                        Logout
                                    </p>
                                </a>
                            </li>
                            <!-- <li class="nav-item dropdown">
                                        <a href="http://localhost/expense_budget/admin/?page=system_info"
                                            class="nav-link nav-system_info">
                                            <i class="nav-icon fas fa-cogs"></i>
                                            <p>
                                                Settings
                                            </p>
                                        </a>
                                    </li> -->
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
            </div>
        </div>
        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar-corner"></div>
    </div>
    <!-- /.sidebar -->
</aside>
<!-- <script>
            $(document).ready(function () {
                var page = 'budget';
                var s = '';
                page = page.split('/');
                page = page[0];
                if (s != '')
                    page = page + '_' + s;

                if ($('.nav-link.nav-' + page).length > 0) {
                    $('.nav-link.nav-' + page).addClass('active')
                    if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
                        $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
                        $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
                    }
                    if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
                        $('.nav-link.nav-' + page).parent().addClass('menu-open')
                    }

                }

            })
        </script> -->