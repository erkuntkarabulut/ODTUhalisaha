<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// public/index.php

// Otomatik yükleyici (Autoloader)
spl_autoload_register(function($class) {
    $base_dir = __DIR__ . '/../';
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Gerekli core dosyalarını yüklüyoruz
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/View.php';
// Router.php opsiyoneldir; bu örnekte basit GET tabanlı routing kullanacağız

// Basit yönlendirme (routing) : URL üzerinden controller ve action belirleniyor.
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';


$controllerClass = 'app\\controllers\\' . ucfirst($controller) . 'Controller';
if (class_exists($controllerClass)) {
    $controllerObject = new $controllerClass();
    if (method_exists($controllerObject, $action)) {
        $controllerObject->$action();
    } else {
        echo "Metod $action bulunamadı.";
    }
} else {
    echo "Controller $controllerClass bulunamadı.";
}

