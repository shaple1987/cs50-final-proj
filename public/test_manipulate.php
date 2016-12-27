<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        apologize("Invalid operation.");
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // discard test
        if (!empty($_POST["discard"]))
        {
            $test_id = $_POST["discard"];
            
            // delete records from test table
            CS50::query("DELETE FROM test WHERE id = ?", $test_id);
            
            // delete record from trial table
            CS50::query("DELETE FROM trial WHERE test_id = ?", $test_id);
            
            // redirect to profile page
            redirect("/");
        }
        // resume test
        else if (!empty($_POST["resume"]))
        {
            $msg = explode("_" , $_POST["resume"]);
            $_SESSION["test_id"] = $msg[0];
            $_SESSION["seq"] = $msg[1];
            render("resume.php", ["title" => "Resume Previous Test"]);
        }
    }
?>
