<?php
// Calls a function when a class is loaded.
//In other words, it says that when you try to load a class could you please do this->

spl_autoload_register(function ($class_name) {
    $source = __DIR__ . '/';
    $dirs = [
        'account/',
        'model/',
    ];

    foreach ($dirs as $dir) {
        if (file_exists($source . $dir . $class_name . '.php')) {
            require $source . $dir . $class_name . '.php';
        }
    }
});