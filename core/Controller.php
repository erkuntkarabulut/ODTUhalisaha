<?php
namespace core;

class Controller {
    public function model($model) {
        $modelPath = __DIR__ . '/../app/models/' . $model . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
        } else {
            die("Model file not found: " . $modelPath);
        }
        // Tam nitelikli sınıf adını oluşturuyoruz:
        $className = 'app\\models\\' . $model;
        if (class_exists($className)) {
            return new $className();
        } else {
            die("Model class not found: " . $className);
        }
    }
    
    public function view($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../app/views/' . $view . '.php';
    }
}

