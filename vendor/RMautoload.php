<?php
// RMautoload.php
function RMspl_autoload_register($class) {
  // Il prefisso del namespace per la tua applicazione
  $namespace_prefix = 'RMapp\\';

  // Controlla se la classe appartiene al tuo namespace
  if (strpos($class, $namespace_prefix) === 0) {
      $base_dir = __DIR__ . '/../../RMapp/';
      $relative_class = substr($class, strlen($namespace_prefix));
      $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
      if (file_exists($file)) {
          require $file;
      }
  }
}

// Registra la tua funzione di autoload personalizzata
spl_autoload_register('RMspl_autoload_register');
?>