<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("pwdchg_form.php", ["title" => "Change Password"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["password"]))
        {
            apologize("You must provide your current password.");
        }
        else if (empty($_POST["newpassword"]))
        {
            apologize("You must provide your new password.");
        }
        else if ($_POST["password"] == $_POST["newpassword"])
        {
            apologize("New password cannot be the same as old password.");
        }
        else if ($_POST["newpassword"] != $_POST["confirm_newpassword"])
        {
            apologize("New passwords must match.");
        }

        // query database for user
        $rows = CS50::query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);

        // compare hash of user's input against hash that's in database, if successful, update the password
        if (password_verify($_POST["password"], $rows[0]["hash"]))
        {
            // change password
            CS50::query("UPDATE users SET hash = ? WHERE id = ?", password_hash($_POST["newpassword"], PASSWORD_DEFAULT) , $_SESSION["id"]);
            
            // confirm success
            render("pwdchg.php", ["title" => "Password Changed Successfully"]);
        }

        // else apologize
        apologize("Invalid current password.");
    }

?>
