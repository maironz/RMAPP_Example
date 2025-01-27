<?php
// index.php
namespace RMapp\Public;
use RMapp\Helpers\LangHelper;
use RMapp\Helpers\Helpers;
use RMapp\src\Contracts\ContractController;
use RMapp\src\Employees\EmployeesController;
use Exception;
// Enable error reporting for debugging (disable in production)

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Joomla variables
use Joomla\CMS\Factory;
$articleId = $app->input->getInt('id');
$userId = $user->get( 'id' );

// Custom variables
require_once str_replace('\\', '/',__DIR__ . '/../vendor/RMautoload.php');

try {
  // Set the call stack
	Helpers::setCaller(__CLASS__, __FUNCTION__, __FILE__);

  // Get the language
  $langHelperPHP = new LangHelper('php');
  $PHPTranslations = $langHelperPHP->getLanguage();
  $langHelperJS = new LangHelper('js');
  $JSTranslations = $langHelperJS->getTranslations();

  // Get the requested view
  $view = $_GET['view'] ?? 'default';

  // Map articleId to views or controllers
  $articleViewMap = [
    29 => 'Contract', // The article with ID 29 loads the Contract view
    50 => 'Employee',
    51 => 'User',
  ];

  // Get the view corresponding to the `articleId`
  $view = $articleViewMap[$articleId] ?? 'default'; // Default if the articleId is not in the map

  // Mapping of available views
  $viewsMap = [
    'Contract' => __DIR__ . '/../src/Contracts/ContractView.php',
    'Employee' => __DIR__ . '/../src/Employee/EmployeeView.php',
    'User'     => __DIR__ . '/../src/User/UserView.php',
    // Add other views if necessary
  ];

  // Check if the view exists in the mapping
  if (array_key_exists($view, $viewsMap) && file_exists($viewsMap[$view])) {
    // if the view is 'Contract', call the controller and display
    if ($view === 'Contract') {
      $controller = new ContractController($langHelperPHP, $langHelperJS);
    } else if ($view === 'Employee') {
      //$controller = new EmployeesController();
    }
    $controller->render(); // Display the contract view

  } else {
    // Show an error message or a default view
    http_response_code(404);
    echo $langHelperPHP->translate("view_not_avalabe");
  }
} catch (Exception $e) {
  $messaggio  = " Errore in [" . basename(__FILE__, '.php') ."].[". __FUNCTION__ ."]";
  $messaggio .= " non è stato possibile effettuare il render della vista";
  $messaggio .= " e->Messaggio=".$e->getMessage();
  $messaggio .= " Stack completo: " . Helpers::getCallStack() . Helpers::getLine();
  $error-> write($messaggio);
}
