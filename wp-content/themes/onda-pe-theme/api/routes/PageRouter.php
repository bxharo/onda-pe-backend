<?php

class PageRouter {
    public function __construct()
    {   
        error_log("🚩 LOG: Instanciando PageRouter");
        // Si la API ya se inició, registramos de inmediato. Si no, esperamos al hook.
        if (did_action('rest_api_init')) {
            $this->register_routes();
        } else {
            add_action('rest_api_init', [$this, 'register_routes']);
        }
    }

    public function register_routes() {
        error_log("🚩 LOG: Intentando registrar rutas en custom/v1");
        register_rest_route('custom/v1', '/pages/(?P<slug>[a-zA-Z0-9-]+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'show'),
            'permission_callback' => '__return_true', // Simplificado para probar
            'args'  => $this->__getArgs(['type', 'type-name'])
        ));
    }

    public function show($request) {
        try {
            // Instanciamos el controlador
            $controller = new \App\Controllers\PageController();
            $data = $controller->show($request);

            // Simplemente retornamos el array; WP lo enviará como JSON
            return [
                'code'    => 200,
                'message' => $data ? 'Panda WP content here!!' : 'No Panda WP content 😥',
                'data'    => $data,
                'status'  => $data ? true : false
            ];
        } catch (Exception $e) {
            // En caso de error, devolvemos un objeto de error de WP
            return new WP_Error('api_error', $e->getMessage(), ['status' => 500]);
        }
    }

    private function __getArgs($selectedRules) {
        $rules = [
            'type' => [
                'required'          => true,
                'type'              => 'string',
                'validate_callback' => function($param, $request, $key) {
                    return is_string($param);
                },
            ],
            'type-name' => [
                'required'          => false,
                'type'              => 'string',
                'validate_callback' => function($param, $request, $key) {
                    return is_string($param);
                },
            ]
        ];

        return $selectedRules
            ? array_intersect_key($rules, array_flip($selectedRules))
            : $rules;
    }
}
