<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("pwdreset-step1_form.php", ["title" => "Reset Password"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["email"]))
        {
            apologize("You must provide your email.");
        }

        // query database for user
        $rows = CS50::query("SELECT * FROM users WHERE email = ?", $_POST["email"]);

        // if email not on file, apologize
        if (count($rows) == 0)
        {
            apologize("Invalid email."); 
        }

        // first (and only) row
        $row = $rows[0];

        // delete expired password reset key
        CS50::query("DELETE FROM pwreset WHERE userid = ? and expire < ?", $row["id"], date("Y-m-d H:i:s"));
        
        // insert new password reset key
        //reference on generating random key
        //http://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string/13733588#13733588
        $pwreset_key = bin2hex(openssl_random_pseudo_bytes(16));
        $expire = date("Y-m-d H:i:s", time()+60*60);
        $rows = CS50::query("INSERT IGNORE INTO pwreset (userid, hash, expire) VALUES(?, ?, ?)",
        $row["id"], $pwreset_key, $expire);
        
        if($rows == 0)
        {
            apologize("More than one password reset request within one hour.");
        }
        
        // input validated, prepare email
        
        // email reference: https://manual.cs50.net/mail/
        
        // To bypass Gmail security checks, need to allow access to Gmail account per the stackoverflow post below
        // http://stackoverflow.com/questions/712392/send-email-using-the-gmail-smtp-server-from-a-php-page?rq=1
        // https://accounts.google.com/b/0/DisplayUnlockCaptcha
        // Also, in security setting: Allow less secure apps: ON
        
        require_once("libphp-phpmailer/class.phpmailer.php");

        // instantiate mailer
        $mail = new PHPMailer();

        // use your ISP's SMTP server (e.g., smtp.fas.harvard.edu if on campus or smtp.comcast.net if off campus and your ISP is Comcast)
        $mail->IsSMTP();
        $mail->IsHTML();
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "tls";                 // sets the prefix to the server

        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 587;                   // 587 for tls; 465 for ssl (deprecated)
        $mail->Username   = "shaple1987.cs50@gmail.com";  // GMAIL username
        $mail->Password   = "cs50123456";  
        
        // set From:
        $mail->SetFrom("shaple1987.cs50@gmail.com");

        // set To:
        $mail->AddAddress($row["email"]);

        // set Subject:
        $mail->Subject = $row["username"].', Please Reset Your Password';

        // set body
        $mail->Body = "<html><body><p>Please reset your password using the link below.</p>";
        $mail->Body .= "<p><a href='https://ide50-shaple1987.cs50.io/pwdreset-step2.php?k=".$pwreset_key."'>Password Reset Link</a></p>";
        $mail->Body .= "<p> This link will expire after ".$expire.".</p></body></html>";

        // set alternative body, in case user's mail client doesn't support HTML
        //$mail->AltBody = "hello, world";

        // send mail
        if ($mail->Send())
        {
            render("pwdreset-step1.php", ["title" => "Password Reset Email Sent"]);
        }
        else
        {
            //delete password reset key
            CS50::query("DELETE FROM pwreset WHERE userid = ?", $row["id"]);
            apologize('Unable to send email. Please try again.');
        }
    }

?>
