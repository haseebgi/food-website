<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">

        <div class="sb-sidenav-menu">

            <div class="nav">

                <div class="sb-sidenav-menu-heading">
                    Main
                </div>

              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
                <div class="sb-sidenav-menu-heading">
                    Management
                </div>

               <a class="nav-link" href="{{ route('categories.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    Categories
                </a>

               <a class="nav-link" href="{{ route('products.index') }}">
                <div class="sb-nav-link-icon">
                    <i class="fas fa-box"></i>
                </div>
                Products
            </a>

               <li class="nav-item">
                <a class="nav-link" href="{{ route('customers.index') }}">
                    <i class="fas fa-user-friends"></i>
                    <span>Customers</span>
                </a>
            </li>

              <li class="nav-item">
                    <a class="nav-link" href="{{ route('orders.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </li>

                    <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>


                <li class="nav-item">
                <a class="nav-link" href="{{ route('suppliers.index') }}">
                    <i class="fas fa-truck"></i>
                    <span>Suppliers</span>
                </a>
            </li>


            <li class="nav-item">
            <a class="nav-link" href="{{ route('purchases.index') }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Purchases</span>
            </a>
        </li>


            <li class="nav-item">
                    <a class="nav-link" href="{{ route('payments.index') }}">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Payments</span>
                    </a>
            </li>

            <li class="nav-item">
            <a class="nav-link" href="{{ route('expenses.index') }}">
                <i class="fas fa-fw fa-wallet"></i>
                <span>Expenses</span>
            </a>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="{{ route('expense-categories.index') }}">
                <i class="fas fa-fw fa-list-alt"></i>
                <span>Expense Categories</span>
            </a>
        </li>
        
            <a class="nav-link" href="{{ route('reports.index') }}">
                <div class="sb-nav-link-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                Reports & Analytics
            </a>



                    <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    Inventory
                </a>

            </div>

        </div>

        <div class="sb-sidenav-footer">
            <div class="small">
                Logged in as:
            </div>

            Admin
        </div>

    </nav>
</div>