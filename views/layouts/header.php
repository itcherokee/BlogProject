<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>


    <link href="/content/css/styles.css" rel="stylesheet">
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
                <span class="navbar-brand"><em>PepoBlogSystem</em></span>
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

