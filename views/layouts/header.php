<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <link href="/content/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    <!--    <link href="/content/css/bootstrap.min.css" rel="stylesheet">-->
<!--    <link href="/content/css/bootstrap-theme.min.css" rel="stylesheet">-->
    <script src="/content/js/jquery-2.1.1.min.js"></script>
<!--    <script src="/content/js/bootstrap.min.js"></script>-->
    <title>
        <?php if (isset($this->title)) echo htmlspecialchars($this->title) ?>
    </title>
</head>

<body class="container">
<header class="row">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Brand</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="/">All Blogs<span class="sr-only">(current)</span></a></li>
                    <?php
                    if ($this->isLoggedIn()) {
                        echo "<li><a href='/" . htmlspecialchars($this->getUsername()) . "/posts/index'>My Blog</a></li>";
                    }
                    ?>
                </ul>
                <form class="navbar-form navbar-right" role="search" method="POST" action="/">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search by tag...">
                            <span class="input-group-btn"><button class="btn btn-default" type="button">Go!
                                </button></span>
                        </div>
                        <!-- /input-group -->
                    </div>
                    <!-- /.col-lg-6 -->
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if ($this->isLoggedIn()) {
                        echo "<li class='navbar-text'> Hello, " . htmlspecialchars($this->getUsername()) . "</li>";
                        echo "<li>";
                        echo '<form class="navbar-form navbar-right" action="/home/users/logout" method="POST">';

                        echo '<button type="submit" class="btn btn-default">Logout</button>';
                        echo "</form>";
                        echo "</li>";
                    } else {
                        echo "<li>";
                        echo '<form class="navbar-form navbar-right" action="/home/users" method="POST">';
                        echo '<input type="submit" class="btn btn-default" value="Login" />';
                        echo "</form>";
                        echo "</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main class="row">
    <section class="col-xs-12 col-sm-10 col-sm-offset-1">
        <?php include('messages.php') ?>
    </section>
    <section class="col-xs-12">

