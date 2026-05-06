<div class="vendor-sidebar">

    <h4 class="text-center mb-4">
        Vendor Panel
    </h4>

    <a href="#">
        <i class="fa fa-dashboard me-2"></i>
        Dashboard
    </a>

    <a href="#">
        <i class="fa fa-box me-2"></i>
        Products
    </a>

    <a href="#">
        <i class="fa fa-shopping-cart me-2"></i>
        Orders
    </a>

    <a href="#">
        <i class="fa fa-money-bill me-2"></i>
        Earnings
    </a>

    <a href="#">
        <i class="fa fa-user me-2"></i>
        Profile
    </a>

    <form action="{{ route('vendor.logout') }}"
          method="POST">

        @csrf

        <button type="submit"
                class="btn btn-danger w-100 mt-4">

            Logout

        </button>

    </form>

</div>