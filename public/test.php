<?php

    // configuration
    require("../includes/config.php"); 

    // test global setting
    $_SESSION["N"] = 3;

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        if (empty($_SESSION["test_id"]))
        {
            render("instruction.php", ["title" => "Test Instructions"]);            
        }
    }
    
    if (isset($_POST["submit"]))
    {
        $response = $_POST["submit"];
        if($response != "next")
        {
            // Record response
            CS50::query("UPDATE trial SET resp = ? WHERE test_id = ? and seq = ?", $response, $_SESSION["test_id"], $_SESSION["seq"]);
            // Show "Next" or "Complete" button
            if ($_SESSION["seq"] == $_SESSION["N"])
            {
                // Record end of test
                CS50::query("UPDATE test SET end = ? WHERE id = ?", date("Y-m-d H:i:s"), $_SESSION["test_id"]);
            }
            $next = next_trial();
            $_SESSION["this_page"] = "next.php";
            render("next.php", ["title" => $next["title"], "buttontext" => $next["buttontext"]]);
        }
    }
        
    // initialization
    if (empty($_SESSION["test_id"]))
    {
        $_SESSION["seq"] = 0;
        // generate random seq
        $rand = range(1, $_SESSION["N"]);
        shuffle($rand);
        // Insert test record into database
        CS50::query("INSERT INTO test(user_id, n_trial, start) VALUES(?, ?, ?)",$_SESSION["id"], $_SESSION["N"], date("Y-m-d H:i:s"));
        $rows = CS50::query("SELECT LAST_INSERT_ID() AS id");
        $_SESSION["test_id"] = $rows[0]["id"];
        // Insert trial records into database (all at once)
        for ($i = 1; $i <= $_SESSION["N"]; $i++)
        {
            $rows = CS50::query("SELECT rime1, rime2, foil FROM stimuli WHERE id = ?",  $rand[$i - 1]);
            $audio = $rows[0];
            $correct = $audio["foil"];
            shuffle($audio);
            $correct_loc = array_search($correct, $audio, TRUE);
            CS50::query("INSERT INTO trial(test_id, seq, w1, w2, w3, corr) VALUES(?, ?, ?, ?, ?, ?)", $_SESSION["test_id"], $i, $audio[0], $audio[1], $audio[2], $correct_loc);
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if ($_SESSION["seq"] < $_SESSION["N"])
        {
            $_SESSION["seq"]++;
            $rows = CS50::query("SELECT w1, w2, w3 FROM trial WHERE test_id = ? and seq = ?",  $_SESSION["test_id"],  $_SESSION["seq"]);
            $audio = $rows[0];
            foreach($audio as &$a) $a .= ".wav";
            $playaudio = "1";
            // render play.php
            $_SESSION["this_page"] = "play.php";
            render("play.php", ["title" => "Test In Progress", "audio" => $audio, "playaudio" => $playaudio]);
        }
        else
        {
            redirect("/");
        }
    }
    else // user tries to refresh the test page while test is in progress, do not insert new trial record and do not replay audio 
    {
        if ($_SESSION["this_page"] == "play.php")
        {
            $audio = ['donotexist.wav', 'donotexist.wav', 'donotexist.wav'];
            $playaudio = "0";
            render("play.php", ["title" => "Test In Progress", "audio" => $audio, "playaudio" => $playaudio]);
        }
        else if ($_SESSION["this_page"] == "next.php")
        {
            // "Reload" next.php without writing to database again
            $next = next_trial();
            render("next.php", ["title" => $next["title"], "buttontext" => $next["buttontext"]]);
        }
    }

?>
