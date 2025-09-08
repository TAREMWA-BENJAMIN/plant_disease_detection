<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PlantD - Plant Disease Detector</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&amp;display=swap" rel="stylesheet">

    <!-- Plugin CSS -->
    <link href="{{ asset('files/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
    <link href="{{ asset('files/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('files/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Common CSS -->
    <link href="{{ asset('files/js-css/app.css') }}" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('files/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
</head>
<body>
    <div class="main-wrapper" id="app">
        @include('layouts.partials.sidebar')
        <div class="page-wrapper">
            @include('layouts.partials.header')
            @yield('content')
            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('files/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('files/js-css/app.js') }}"></script>

    <!-- Plugin JS -->
    <script src="{{ asset('files/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('files/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>

    <!-- Common JS -->
    <script src="{{ asset('files/js/template.js') }}"></script>
    <script src="{{ asset('files/js/dashboard.js') }}"></script>
    
    <!-- DataTables Load Test -->
    <script>
        // Wait for jQuery to be fully loaded
        function waitForJQuery() {
            if (typeof $ !== 'undefined') {
                console.log('jQuery version:', $.fn.jquery);
                console.log('jQuery loaded:', typeof $ !== 'undefined');
                
                // Now load DataTables
                loadDataTables();
            } else {
                console.log('jQuery not ready yet, retrying...');
                setTimeout(waitForJQuery, 100);
            }
        }
        
        function loadDataTables() {
            console.log('Loading DataTables...');
            
            // Check if DataTables is already loaded
            if (typeof $.fn.DataTable !== 'undefined') {
                console.log('DataTables already loaded');
                console.log('DataTables version:', $.fn.dataTable.version);
                console.log('DataTables ready for use');
                return;
            }
            
            // Debug: Log the URLs being used
            var coreUrl = '{{ asset("files/plugins/datatables-net/jquery.dataTables.js") }}';
            var bootstrapUrl = '{{ asset("files/plugins/datatables-net-bs5/dataTables.bootstrap5.js") }}';
            console.log('Core URL:', coreUrl);
            console.log('Bootstrap URL:', bootstrapUrl);
            
            // Load DataTables core
            var script1 = document.createElement('script');
            script1.src = coreUrl;
            script1.onload = function() {
                console.log('DataTables core loaded successfully');
                
                // Load DataTables Bootstrap
                var script2 = document.createElement('script');
                script2.src = bootstrapUrl;
                script2.onload = function() {
                    console.log('DataTables Bootstrap loaded successfully');
                    console.log('DataTables loaded:', typeof $.fn.DataTable !== 'undefined');
                    
                    if (typeof $.fn.DataTable !== 'undefined') {
                        console.log('DataTables version:', $.fn.dataTable.version);
                        console.log('DataTables ready for use');
                    } else {
                        console.error('DataTables still not available after loading');
                        console.log('Available jQuery plugins:', Object.keys($.fn));
                    }
                };
                script2.onerror = function() {
                    console.error('Failed to load DataTables Bootstrap from:', bootstrapUrl);
                };
                document.head.appendChild(script2);
            };
            script1.onerror = function() {
                console.error('Failed to load DataTables core from:', coreUrl);
            };
            document.head.appendChild(script1);
        }
        
        // Start the process when DOM is ready
        $(document).ready(function() {
            console.log('DOM ready, elements count:', $('*').length);
            waitForJQuery();
        });
    </script>

    @stack('scripts')

    <!-- Feather Icons JS - Loaded last to ensure it runs after all other scripts -->
    <script src="{{ asset('files/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('files/js/feather-icons-init.js') }}"></script>

    <style>
        html, body, .main-wrapper, .page-wrapper {
            min-height: 100vh;
            height: 100%;
        }
        body {
            background: linear-gradient(135deg, #f8faf5 0%, #f1f8e9 100%) !important;
        }
        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(135deg, #f1f8e9 0%, #e8f5e8 100%) !important;
        }
        .main-wrapper {
            background: linear-gradient(135deg, #f8faf5 0%, #f1f8e9 100%) !important;
        }
        .page-wrapper > *:not(.footer) {
            flex-shrink: 0;
        }
        .footer {
            margin-top: auto;
        }
        /* Override any conflicting styles */
        html body {
            background: linear-gradient(135deg, #f8faf5 0%, #f1f8e9 100%) !important;
        }
        html body .main-wrapper {
            background: linear-gradient(135deg, #f8faf5 0%, #f1f8e9 100%) !important;
        }
        html body .page-wrapper {
            background: linear-gradient(135deg, #f1f8e9 0%, #e8f5e8 100%) !important;
        }
        /* Force override for all possible selectors */
        body, html body, body.main-wrapper, body .main-wrapper, body .page-wrapper {
            background: linear-gradient(135deg, #f8faf5 0%, #f1f8e9 100%) !important;
        }
        .page-wrapper, body .page-wrapper, html body .page-wrapper {
            background: linear-gradient(135deg, #f1f8e9 0%, #e8f5e8 100%) !important;
        }
    </style>
</body>
</html>