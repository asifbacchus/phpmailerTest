# Simple PHPMailer Test Script <!-- omit from TOC -->

Simple drop-in test page and script with no dependancies (outside the files in this repo) to determine if PHPMailer is working, if your ISP/host is blocking ports or if your mailserver doesn't like something you're doing.

- [usage](#usage)
- [configuration](#configuration)
- [on-page options](#on-page-options)
- [use-cases](#use-cases)
- [a word of caution](#a-word-of-caution)
- [final thoughts](#final-thoughts)

This script requires NO complex configuration or programming experience. Just provide your SMTP connection information, choose some combination of settings on the test page and read the formatted diagnostic logs to see what's going on.

This can be a welcome alternative when PHPMailer has been integrated into a complex piece of software like an e-commerce platform. Instead of fishing through thousands of lines of code to enable debugging output, you can use this simple test page to see the exact error(s) reported by your mail server.

## usage

1. Clone or extract this repo to the root of your website.
2. Fill-in your SMTP connection parameters in **config.php** and save the file.
3. Navigate to *your.domain.tld/mailertest*.
4. Fill-in the on-page options and click the 'TEST' button.
5. Read the full diagnostic log provided on screen.

## configuration

The *only* file you need to alter is **config.php**. Simply provide the information requested by the clearly named variables.

|variable|content|
|:---|:---|
|timeout|Time in seconds before terminating the SMTP connection attempt. Defaults to 15 seconds.|
|hostname|Address of your SMTP server. Example: mail.server.com|
|username|Username of account authorized to connect to the SMTP server.|
|password|Password of account authorized to connect to the SMTP server.|

## on-page options

- Ports: Select one of the commonly defined ports or specify an arbitrary port. **Note: Arbitrary port will override all other choices.**
- Encryption: Select either no encryption, SSL or STARTTLS. Note that you can select a type of encryption that defies the standard setup as determined by your port choice. For example, you can select STARTTLS despite selecting port 465 which is 99% of the time SSL. This will likely generate an error that will appear in the displayed diagnostic log -- that is the point of this test-page :-)
- Recipient: Address to which you want the test email sent.
- Reply To: Address which will be used as the reply address of the test message.

## use-cases

- Since this repo is fully self-contained (all required files, modules, etc. are contained herein), it can be used to determine if your webhost supports PHPMailer independant of any other installed programs/scripts/etc.
- Since you can choose ports, you can easily check if your service provider/host is blocking certain ports (you will see a connection failure in the diagnostic logs).
- You can choose various combinations of encryption/no-encryption to determine how your target mail server is configured on particular ports.
- You can test your mail server's support for a reply address which may not correspond to the authenticated submitting account.
- Because an unabridged debug diagnostic log is clearly displayed, you can see each stage of the mail submission transaction, whether successful or not.
- The PHPMailer used by this test-page is independant and can be used to confirm/diagnose other installations of PHPMailer. For example, you can copy the settings you are using in another program and then use the debugging output provided by this script to see what's happening but not reported by the other program.

## a word of caution

The test page provided has NO safeguards against being used for spamming or other forms of abuse against a mail server or other users. Therefore, only keep this page installed/accessible for as long as you need to generate debug logs **then delete/disable the test-page!**

## final thoughts

I hope you find a use for this script and it helps you solve any problems you're experiencing using PHPMailer. I am NOT affiliated with PHPMailer, I just cobbled this together as a quick way to test unfamiliar webhosts and confirm my configurations are correct.

If you have any suggestions, feature-requests or find any bugs, please submit an issue!
