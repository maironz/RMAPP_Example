<?php
// 01_AutoloaderTest.php
namespace RMapp\Tests;
// Joomla variables
echo "<br><b> Inizio 01_AutoloaderTest</b><br>";

use Joomla\CMS\Factory;
$articleId = $app->input->getInt('id');
$userId = $user->get( 'id' );

require_once str_replace('\\', '/',__DIR__ . '/../vendor/RMautoload.php');
use RMapp\Config\Settings;
$set = Settings::getInstance();
$mail = $set->get('MAIL_TO');
echo "MAIL_TO: $mail";
echo "<b><br> Fine 01_AutoloaderTest <br> Risultato OK <br></b>";
?>