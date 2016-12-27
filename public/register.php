<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["username"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["email"]))
        {
            apologize("You must provide your email.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }
        else if ($_POST["password"] != $_POST["confirmation"])
        {
            apologize("Passwords must match.");
        }

        // query database for user
        $rows = CS50::query("INSERT IGNORE INTO users (username, email, hash, cash) VALUES(?, ?, ?, 10000.0000)",
        $_POST["username"], $_POST["email"], password_hash($_POST["password"], PASSWORD_DEFAULT));

        // if we found user, check password
        if ($rows == 0)
        {
            apologize("Invalid username and/or email.");
        }
        else
        {
            // remember that user's now logged in by storing user's ID in session
            $rows = CS50::query("SELECT LAST_INSERT_ID() AS id");
            $_SESSION["id"] = $rows[0]["id"];
            $rows = CS50::query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
            print($rows);
            
            $_SESSION["username"] = $rows[0]["username"];
            
            // redirect to portfolio
            redirect("/");
        }

    }

?>