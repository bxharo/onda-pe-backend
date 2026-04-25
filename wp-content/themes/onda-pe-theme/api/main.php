<?php
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/database/main.php");

error_log("🚩 LOG: Entrando a api/main.php");
// ... después de los importProviders
error_log("🚩 LOG: Routers importados");

// 1. Cargar modelos
$appModels = ['Example']; 
__importProviders('model', $appModels);

// 2. Cargar Controladores (la lógica de tus rutas)
$appControllers = ['PageController', 'ExampleController'];
__importProviders('controller', $appControllers);
// 3. Cargar rutas
$appRoutes = ['PageRouter', 'ExampleRouter'];
__importProviders('router', $appRoutes);

foreach ($appRoutes as $routeClass) {
    if (class_exists($routeClass)) {
        new $routeClass(); // Aquí se ejecuta el __construct y se registra la ruta
    }
}

// TEST RÁPIDO:
add_action('rest_api_init', function() {
    register_rest_route('panda/v1', '/test', [
        'methods' => 'GET',
        'callback' => function() { return ['status' => 'OK', 'msg' => 'La API está viva']; },
        'permission_callback' => '__return_true'
    ]);
});