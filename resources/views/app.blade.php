<!doctype>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Accountant</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css', 'vendor/accountant') }}">
    </head>

    <body>

        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="{{ url('accountant') }}" class="navbar-brand visible-xs">Laravel Accountant</a>
                </div>

                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="{{ request()->is('accountant') ? 'active' : null }}">
                            <a href="{{ url('accountant') }}">Dashboard</a>
                        </li>
                        <li class="{{ request()->is('accountant/charges', 'accountant/charges/*') ? 'active' : null }}">
                            <a href="{{ url('accountant/charges') }}">Charges</a>
                        </li>
                        <li class="{{ request()->is('accountant/customers', 'accountant/customers/*') ? 'active' : null }}">
                            <a href="{{ url('accountant/customers') }}">Customers</a></li>
                        <li class="{{ request()->is('accountant/subscriptions', 'accountant/subscriptions/*') ? 'active' : null }}">
                            <a href="{{ url('accountant/subscriptions') }}">Subscriptions</a>
                        </li>
                        <li class="{{ request()->is('accountant/invoices', 'accountant/invoices/*') ? 'active' : null }}">
                            <a href="{{ url('accountant/invoices') }}">Invoices</a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right hidden-xs">
                        <li>
                            <a href="https://github.com/tomirons/laravel-accountant" target="_blank"><i class="fa fa-github"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div id="app" class="container">
            <div class="row">
                <div class="col-md-12">
                    @yield('content')
                </div>
            </div>
        </div>

        <script src="{{ mix('js/app.js', 'vendor/accountant') }}"></script>
    </body>
</html>