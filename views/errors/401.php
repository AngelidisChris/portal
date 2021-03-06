
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Error - 401</title>

    <script src="/resources/jquery-3.2.1/jquery-3.2.1.min.js"></script>
    <link href="/resources/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/resources/css/signin.css" rel="stylesheet">
    <link href="/resources/css/error_page.css" rel="stylesheet">
</head>

<body>

<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand">Portal</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

        </div><!--/.nav-collapse -->
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>
                    Oops!</h1>
                <h2>
                    401 Unauthorized</h2>
                <div class="error-details">
                    <p>You have attempted to access a page for which you are not authorized.</p>
                    <p>Try to Sign In.</p>
                </div>
                <div class="error-actions">
                    <a href="/auth/login" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-log-in"></span>
                        &nbsp;Take Me to Sign in </a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
