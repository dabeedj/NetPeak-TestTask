<?php

/**
 * autoloader - loads classes automatically
 */
function autoloader($class_name)
{
    // Folders with needed classes
    $array_paths = array(
        '/models/',
        '/components/',
        '/controllers/',
    );

    // Folders iteration
    foreach ($array_paths as $path) {

        // Make class path
        $path = ROOT . $path . $class_name . '.php';

        // Include if class file exists
        if (is_file($path)) {
            include_once $path;
        }
    }
}
