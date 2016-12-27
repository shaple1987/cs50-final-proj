<!DOCTYPE html>

<html>

    <head>

        <!-- http://getbootstrap.com/ -->
        <link href="/css/bootstrap.min.css" rel="stylesheet"/>

        <link href="/css/styles.css" rel="stylesheet"/>

        <?php if (isset($title)): ?>
            <title>Rhyme Oddity Test: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>Rhyme Oddity Test</title>
        <?php endif ?>

        <!-- https://jquery.com/ -->
        <script src="/js/jquery-1.11.3.min.js"></script>

        <!-- http://getbootstrap.com/ -->
        <script src="/js/bootstrap.min.js"></script>

        <script src="/js/scripts.js"></script>

    </head>

    <body>

        <div class="container">

            <div id="top">
                <div>
                    <a href="/"><img alt="C$50 Finance" src="/img/logo.png"/></a>
                </div>
                <?php if (!empty($_SESSION["id"])): ?>
                    <div>
                        Welcome <b><?= htmlspecialchars($_SESSION["username"]) ?></b>!
                    </div>
                    <?php if (empty($_SESSION["test_id"])): ?>
                        <ul class="nav nav-pills">
                            <li><a href="pwdchg.php">Change Password</a></li>
                            <li><a href="logout.php"><strong>Log Out</strong></a></li>
                        </ul>
                    <?php else: ?>
                        <p><font color='red'> Test In Progress!  Please do not use the browser "back" or "reload" buttons.</font></p>
                    <?php endif ?>
                <?php endif ?>
                
            </div>

            <div id="middle">
