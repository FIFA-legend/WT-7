<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main</title>
    <link rel="stylesheet" href="static/main_styles.css" type="text/css">
</head>
<body>
<header>
    <div class="header-container">
        <span>Mail</span>
    </div>
</header>
<main>
    <?php
    use PHPMailer\PHPMailer\PHPMailer;
    const tel_regex = "/^\+375(29|33|44|25)\d{7}\$/";
    $name = "";
    $telephone = "";
    $email = "";
    $password = "";
    $subject = "";
    $mail = "";
    $correct_tel = true;
    $correct_subject = true;
    $correct_mail = true;
    $correct_send = true;
    if (isset($_POST["name"])) {
        $name = $_POST["name"];
    }
    if (isset($_POST["telephone"])) {
        $telephone = $_POST["telephone"];
    }
    if (isset($_POST["email"])) {
        $email = $_POST["email"];
    }
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }
    if (isset($_POST["subject"])) {
        $subject = $_POST["subject"];
    }
    if (isset($_POST["mail"])) {
        $mail = $_POST["mail"];
    }
    if (!preg_match(tel_regex, $telephone)) {
        $correct_tel = false;
    }
    if (strlen($subject) < 15 || strlen($subject) > 140) {
        $correct_subject = false;
    }
    if (strlen($mail) < 15) {
        $correct_mail = false;
    }
    if ($correct_mail && $correct_subject && $correct_tel) {
        $message = wordwrap($mail, 70, "\r\n");
        $answer_subject = "Message received";
        $answer_text = "Thanks for your feedback. We will answer you soon";
        require_once "PHPMailer\PHPMailer.php";
        require_once "PHPMailer\SMTP.php";
        require_once "PHPMailer\Exception.php";
        $sender_mailer = new PHPMailer();
        $sender_mailer->IsSMTP();
        $sender_mailer->Host = "ssl://smtp.gmail.com:465";
        $sender_mailer->SMTPAuth = TRUE;
        $sender_mailer->Username = $email;
        $sender_mailer->Password = $password;
        $sender_mailer->From = $email;
        $sender_mailer->FromName = $name;
        $sender_mailer->Body = $message;
        $sender_mailer->Subject = $subject;
        $sender_mailer->AddAddress("kolodkonikita20010508@gmail.com");
        if ($sender_mailer->Send()) {
            $receiver_mailer = new PHPMailer();
            $receiver_mailer->IsSMTP();
            $receiver_mailer->Host = "ssl://smtp.gmail.com:465";
            $receiver_mailer->SMTPAuth = TRUE;
            $receiver_mailer->Username = "kolodkonikita20010508@gmail.com";
            $receiver_mailer->Password = "0987654321KnKn";
            $receiver_mailer->From = "kolodkonikita20010508@gmail.com";
            $receiver_mailer->FromName = "Kolodko Nikita";
            $receiver_mailer->Body = $answer_text;
            $receiver_mailer->Subject = $answer_subject;
            $receiver_mailer->AddAddress($email);
            $receiver_mailer->Send();
            $name = "";
            $telephone = "";
            $email = "";
            $subject = "";
            $mail = "";
        } else {
            $correct_send = false;
        }
    }
    ?>
    <form action="index.php" method="post">
        <?php
        echo "<input name='name' type='text' placeholder='Name:' value='$name' required> <br>";
        echo "<input name='telephone' type='tel' placeholder='Telephone:' value='$telephone' required> <br>";
        if (!$correct_tel && $_POST) {
            echo "<p class='error_message'>Telephone number is incorrect</p>";
        }
        echo "<input name='email' type='email' placeholder='Email:' value='$email' required> <br>";
        echo "<input name='password' type='password' placeholder='Password:' required> <br>";
        if (!$correct_send) {
            echo "<p class='error_message'>Incorrect password</p>";
        }
        echo "<input name='subject' type='text' placeholder='Subject:' value='$subject' required> <br>";
        if (!$correct_subject && $_POST) {
            echo "<p class='error_message'>Subject length should be between 15 and 140 characters</p>";
        }
        echo "<textarea name='mail' cols='68' rows='20' placeholder='Mail Text:' required>$mail</textarea> <br>";
        if (!$correct_mail && $_POST) {
            echo "<p class='error_message'>Message text should be larger</p>";
        }
        ?>
        <button type="submit">Send</button>
    </form>
</main>
</body>
</html>