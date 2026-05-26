<div class="vendor-header d-flex justify-content-between align-items-center">

    <h4 class="mb-0">
        @yield('title')
    </h4>

    <div>

        Welcome,

        <strong>
            {{ auth('vendor')->user()->name ?? 'Vendor' }}
        </strong>

    </div>

</div>