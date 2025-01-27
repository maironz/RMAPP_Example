<?php
// test.php
declare(strict_types=1);
namespace RMapp\Public;

require_once str_replace('\\', '/',__DIR__ . '/../tests/01_AutoloaderTest.php');
require_once str_replace('\\', '/',__DIR__ . '/../tests/02_LogTest.php');
require_once str_replace('\\', '/',__DIR__ . '/../tests/03_ConnectionTest.php');
require_once str_replace('\\', '/',__DIR__ . '/../tests/04_ContractTest.php');
require_once str_replace('\\', '/',__DIR__ . '/../tests/05_HelpersTest.php');

?>