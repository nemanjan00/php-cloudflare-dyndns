<?php
include("./Class/cloudflare.class.php");

$mail = "YOUREMAIL";
//Get it at https://www.cloudflare.com/my-account
$api = "YOURAPIKEY";

$cloudflare = new cloudflare("YOURDOMAIN", $mail, $api);

//This will update SUBDOMAIN.YOURDOMAIN A record to your IP
$cloudflare->update("SUBDOMAIN.YOURDOMAIN");

//This will print IP that it is going be set as A record value
echo $cloudflare->getip();

//This will set IP that it is going be set as A record value
$cloudflare->setip("127.0.0.1");

//This will update SUBDOMAIN.YOURDOMAIN A record to 127.0.0.1
$cloudflare->update("SUBDOMAIN.YOURDOMAIN");
?>
