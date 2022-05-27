<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex,nofollow">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Not Available Offline</title>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="am-splash-screen">
    <div class="am-wrapper am-error am-error-404">
        <div class="am-content">
            <div class="main-content">
                <div class="error-container">
                    <!-- <div class="error-image"></div> -->
                    <div class="error-number">!</div>
                    <p class="error-description">The page you have requested is not available offline.</p>
                    <div class="error-goback-text">Please go online to view it.
                        @auth
                        Alternatively you can go back to <a href="/proposals/list">all proposals.</a>
                        @endauth
                    </div>
                    <!-- <div class="footer">&copy; 2015 <a href="#">Your Company</a></div> -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>