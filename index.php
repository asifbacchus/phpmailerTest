<?php
// show all errors and set global timeout
include_once('php/00_showErrors.include.php');

// include SMTP configuration and mailer function
include_once('config.php');
include_once('php/PHPMailer/mailer.php');

function missingConfig($missingParam){
    echo '<h1>Configuration Error</h1>';
    echo '<p>Please re-check <span style="font-weight: bold">config.php</span>. It appears you forgot to provide a value for <span style="font-weight: bold">' . $missingParam . '</span>.</p>';
    echo '<p>Unable to continue until configuration error has been resolved.</p>';
    die();
}

// global variables
$output = $usePort = $useEncryption = $recipient = $replyTo = NULL;

// check configuration for missing entries
if (!$SMTP['timeout']) $SMTP['timeout'] = 15;
if (!$SMTP['hostname']) missingConfig('hostname');
if (!$SMTP['username']) missingConfig('username');
if (!$SMTP['password']) missingConfig('password');

// process POST request if made
if (isset($_POST) && !empty($_POST)){
    // retain settings
    if (!empty($_POST['port'])){
        $usePort = $_POST['port'];
    } else{
        $output .= '<< no port selected >><br>';
    }
    if (!empty($_POST['encryption'])){
        $useEncryption = $_POST['encryption'];
    } else{
        $output .= '<< no encryption selected >><br>';
    }
    if (!empty($_POST['recipient'])){
        $recipient = filter_var($_POST['recipient'], FILTER_VALIDATE_EMAIL) ? $_POST['recipient'] : $output .= '<< no recipient address specified >><br>';
    } else{
        $output .= '<< no recipient address specified >><br>';
    }
    if (!empty($_POST['replyTo'])){
        $replyTo = filter_var($_POST['replyTo'], FILTER_VALIDATE_EMAIL) ? $_POST['replyTo'] : $output .= '<< no reply address specified >><br>';
    } else{
        $output .= '<< no reply address specified >><br>';
    }
    // send email IFF no NULL fields
    if ($usePort && $useEncryption && $recipient && $replyTo){
        $body = 'This is a test message from the AB-GROUP PHP Email Port Tester PHP script. You may ignore this message.';
        $subject = 'Test message from AB-GROUP Port Test Script';
        $mailResult = sendEmail($SMTP['timeout'], $SMTP['hostname'], $usePort, $useEncryption, $SMTP['username'], $SMTP['password'], $recipient, $replyTo, $body, $subject);
        $output .= $mailResult['debug'];
    }
} else{
    $output = '<< click the TEST button to start testing >>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Port Test</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Email Port Test</h1>
    <p class="notice">*** Remember to remove this page when you are finished testing! This page has NO protections against being used for SPAM or being otherwise abused! ***</p>
    <p>Fill in the form options below and click the 'TEST' button. Diagnostic data will be displayed whether or not the test email was successfully relayed.</p>
    <form action="index.php" method="post" class="form">
        <div class="options">
            <div class="portSelect">
                <p>port:</p>
                <label for="port25" class="radioLabel">
                    <input type="radio" name="port" id="port25" class="btn_radio" value=25 <?php echo (isset($usePort) && $usePort === '25') ? 'checked' : NULL;?>>
                    SMTP (port 25)
                </label>
                <label for="port465" class="radioLabel">
                    <input type="radio" name="port" id="port465" class="btn_radio" value=465 <?php echo (isset($usePort) && $usePort === '465') ? 'checked' : NULL;?>>
                    SMTP/S (port 465)
                </label>
                <label for="port587" class="radioLabel">
                    <input type="radio" name="port" id="port587" class="btn_radio" value=587 <?php echo (isset($usePort) && $usePort === '587') ? 'checked' : NULL;?>>
                    SUBMISSION (port 587)
                </label>
                <label for="port2525" class="radioLabel">
                    <input type="radio" name="port" id="port2525" class="btn_radio" value=2525 <?php echo (isset($usePort) && $usePort === '2525') ? 'checked' : NULL;?>>
                    Alternate SMTP 2525
                </label>
            </div>
            <div class="encryptionSelect">
                <p>encryption:</p>
                <label for="noEnc" class="radioLabel">
                    <input type="radio" name="encryption" id="noEnc" class="btn_radio" value="none" <?php echo (isset($useEncryption) && $useEncryption === 'none') ? 'checked' : NULL;?>>
                    NONE
                </label>
                <label for="ssl" class="radioLabel">
                    <input type="radio" name="encryption" id="ssl" class="btn_radio" value="ssl" <?php echo (isset($useEncryption) && $useEncryption === 'ssl') ? 'checked' : NULL;?>>
                    SSL
                </label>
                <label for="starttls" class="radioLabel">
                    <input type="radio" name="encryption" id="starttls" class="btn_radio" value="starttls" <?php echo (isset($useEncryption) && $useEncryption === 'starttls') ? 'checked' : NULL;?>>
                    STARTTLS
                </label>
            </div>
        </div>
        <div class="addresses">
            <label for="recipient" class="textLabel">
                <span class="bold">Recipient:</span>
                <input type="email" name="recipient" id="recipient" class="textbox" value="<?php echo (isset($recipient)) ? $recipient : ''; ?>">
            </label>
            <label for="replyTo" class="textLabel">
                <span class="bold">Reply To:</span>
                <input type="email" name="replyTo" id="replyTo" class="textbox" value="<?php echo (isset($replyTo)) ? $replyTo : ''; ?>">
            </label>
        </div>
        <div class="buttons">
            <button type="submit" class="btn">TEST</button>
            <button type="reset" class="btn">CLEAR</button>
        </div>
    </form>
    <h2>Diagnostic Output:</h2>
    <div class="diagOutput">
        <!-- phpmailer output here-->
        <?php echo $output; ?>
    </div>
</body>
</html>
