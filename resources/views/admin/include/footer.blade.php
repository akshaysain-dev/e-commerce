<footer class="main-footer" style="
    background: linear-gradient(135deg, #1a1f3c 0%, #2d3561 100%);
    border-top: none;
    padding: 0;
    margin-left: 0;
">
    <div style="padding: 28px 36px;">

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">

            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="
                    width: 36px; height: 36px;
                    border-radius: 10px;
                    background: linear-gradient(135deg, #4e73df, #6f42c1);
                    display: flex; align-items: center; justify-content: center;
                    font-size: 16px; font-weight: 700; color: #fff;
                ">
                    {{ strtoupper(substr(config('app.name', 'S'), 0, 1)) }}
                </div>
                <div>
                    <div style="font-size: 14px; font-weight: 700; color: #fff; letter-spacing: 0.3px;">
                        {{ config('app.name', 'ShopAdmin') }}
                    </div>
                    <div style="font-size: 11px; color: rgba(255,255,255,0.45); margin-top: 1px;">
                        Admin Control Panel
                    </div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="text-align: center;">
                    <div style="font-size: 10px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px;">Version</div>
                    <div style="font-size: 12px; color: rgba(255,255,255,0.75); font-weight: 500; margin-top: 2px;">1.0.0</div>
                </div>
                <div style="width: 1px; height: 28px; background: rgba(255,255,255,0.12);"></div>
                <div style="text-align: center;">
                    <div style="font-size: 10px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px;">Year</div>
                    <div style="font-size: 12px; color: rgba(255,255,255,0.75); font-weight: 500; margin-top: 2px;">{{ date('Y') }}</div>
                </div>
                <div style="width: 1px; height: 28px; background: rgba(255,255,255,0.12);"></div>
                <div style="text-align: center;">
                    <div style="font-size: 10px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px;">Built with</div>
                    <div style="font-size: 12px; color: rgba(255,255,255,0.75); font-weight: 500; margin-top: 2px;">Laravel</div>
                </div>
            </div>

        </div>

        <div style="
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        ">
            <span style="font-size: 11px; color: rgba(255,255,255,0.35);">
                &copy; {{ date('Y') }} {{ config('app.name', 'ShopAdmin') }}. All rights reserved.
            </span>
            <span style="font-size: 11px; color: rgba(255,255,255,0.35);">
                Designed & maintained by <strong style="color: rgba(255,255,255,0.6); font-weight: 500;">Akshay Sain</strong>
            </span>
        </div>

    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

@section('styles')
<style>
  .wrapper {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.content-wrapper {
    flex: 1;
}
</style>
@endsection