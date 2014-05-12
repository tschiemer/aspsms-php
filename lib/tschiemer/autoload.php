<?php

/**
 * Autoloader for tschiemer namespace
 * 
 * Code based on http://www.php-fig.org/psr/psr-0/
 */

spl_autoload_register(function($className){
    
    // autoload for tschiemer namespace
    if (FALSE === strpos($className, 'tschiemer\\')){
        return;
    }
    $className = str_replace('tschiemer\\', '', $className);
    
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    
    require __DIR__ . '/' . $fileName;
});