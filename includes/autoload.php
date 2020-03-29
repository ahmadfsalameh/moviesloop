<?php

    spl_autoload_register('load');

    function load($className) {

        $path      = 'includes/';
        $extension = '.inc.php';
        $file      = $path . strtolower($className) . $extension;

        if(!file_exists($file)){
            return false;
        }

        require_once $file;

    }