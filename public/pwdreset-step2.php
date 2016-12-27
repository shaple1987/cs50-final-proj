<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        //validate reset key
        $key = $_GET["k"];
        if(empty($key))
        {
            redirect("pwdreset-step1.php");
        }
        
        $rows = CS50::query("SELECT * FROM pwreset WHERE hash = ?", $key);

        // if key is not in the db, do not proceed with reset
        if (count($rows) == 0)
        {
            apologize("Invalid password reset link");
        }
        
        // save key in session
        $_SESSION["k"] = $key;
        
        //else render form
        render("pwdreset-step2_form.php", ["title" => "Reset Password"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }
        else if ($_POST["password"] != $_POST["confirmation"])
        {
            apologize("Passwords must match.");
        }
        
        // query database for user
        $rows = CS50::query("SELECT * FROM pwreset WHERE hash = ?", $_SESSION["k"]);
        $id = $rows[0]["userid"];

        // update password
        CS50::query("UPDATE users SET hash = ? WHERE id = ?", password_hash($_POST["password"], PASSWORD_DEFAULT), $id);

        // delete used password reset key
        CS50::query("DELETE FROM pwreset WHERE hash = ?", $_SESSION["k"]);
        
        render("pwdreset-step2.php", ["title" => "Password Reset Successfully"]);
    }

?>
