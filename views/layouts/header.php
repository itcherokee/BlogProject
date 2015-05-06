<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="/content/bootstrap.css"/>
    <script src="/content/bootstrap.js"></script>
    <title>
        <?php if (isset($this->title)) echo htmlspecialchars($this->title) ?>
    </title>
</head>

<body>
<header>
    <!--    <a href="/"><img src="/content/images/site-logo.png"></a>-->
    <ul>
        <li><a href="/">Blogs</a></li>
        <?php
        if ($this->isLoggedIn()) {
            echo "<li><a href='/" . htmlspecialchars($this->getUsername()) . "/posts/index'>My Blog</a></li>";
        }
        ?>
    </ul>
    <?php
    if ($this->isLoggedIn()) {
        echo '<div id="logged-in-username">';
        echo "<span> Hello, " . htmlspecialchars($this->getUsername())  ."</span>";
        echo "<form action= '/home/users/logout' method='POST'><input type='submit' value='Logout'/></form>";
        echo "</div>";
    } else {
        echo "<form action=/home/users/login' method='POST'><input type='submit' value='Login'/></form>";
    }
    ?>
</header>


<?php include('messages.php'); ?>
