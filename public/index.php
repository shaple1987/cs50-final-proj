<?php

    // configuration
    require("../includes/config.php"); 
    
    // clear test-related session variables
    unset($_SESSION["N"]);
    unset($_SESSION["test_id"]);
    unset($_SESSION["seq"]);
    unset($_SESSION["this_page"]);
    
    // look up existing tests for this user
    $rows = CS50::query("SELECT * FROM test WHERE user_id = ?", $_SESSION["id"]);
    
    // look up recent prices
    $tests = [];
    foreach ($rows as $row)
    {
        if (!empty($row["end"])) // complete test
        {
            $status = "Completed";
            $action = "";
        }
        else // in-progress test
        {
            // look up progress of the in-progress test
            $rows = CS50::query("SELECT max(seq) maxseq FROM trial WHERE test_id = ? and resp is not null", $row["id"]);
            $maxseq = $rows[0]["maxseq"];
            if(empty($maxseq)) $maxseq = 0;
            $status = "In Progress (" . $maxseq . " out of " . $row["n_trial"] . " trials completed)";
            $action = "<button type='submit' name='discard' value='" . $row["id"] . "'>Discard</button>";
            $action .= " <button type='submit' name='resume' value='" . $row["id"] . "_" . $maxseq . "'>Resume</button>";
        }
        
            $tests[] = [
                "id" => $row["id"],
                "n_trial" => $row["n_trial"],
                "start" => $row["start"],
                "end" => $row["end"],
                "status" => $status,
                "action" => $action
            ];
    }
    
    // render portfolio
    render("profile.php", ["title" => "My Profile", "tests" => $tests]);

?>
