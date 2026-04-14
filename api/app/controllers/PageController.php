<?php

namespace App\Controllers;

use Timber\Timber;
use Exception;

class PageController {
    public function __construct() {    }

    // ORQUESTADOR: DECIDE A CUÁL DE LAS SIGUIENTES FUNCIONES LLAMAR
    public function show($request) {
        switch ($request['type']) {
            case 'page':
            case 'post-type':
                $post = Timber::get_post([
                    'post_type' => ($request['type'] == 'post-type')
                        ? $request['type-name']
                        : 'page',
                    'name' => $request['slug']
                ]);

                if ($post) {
                    $post->title = $post->title();
                    $post->content = $post->content();
                    // IMPORTANTE: Convertimos el objeto thumbnail en una URL de texto
                    $post->featured_image = $post->thumbnail() ? $post->thumbnail()->src() : null;

                    $pageData = [
                        'post' => $post
                    ];

                    $pageData = array_merge($pageData, $this->__getPostData($request['type'], $request['type-name'], $request['slug'], $post->ID));
                } else {
                    $pageData = false;
                }

                break;

            case 'term':
                $terms = Timber::get_terms([
                    'taxonomy'  => $request['type-name'],
                    'slug'      => $request['slug']
                ]);
                $term = count($terms) ? $terms[0] : false;

                if ($term) {
                    $term->title;

                    $pageData = [
                        'term' => $term
                    ];

                    $pageData = array_merge($pageData, $this->__getTermData($request['type-name'], $request['slug'], $term->ID, $request['parent']));
                } else {
                    $pageData = false;
                }
                break;
            case 'general':
                $pageData = $this->__getGeneralData();
                break;
        }
        return $pageData ? $pageData : false;
    }
    
    // DETERMINA LA LÓGICA ESPECÍFICA PARA CADA TIPO DE POST (TECNOLOGÍA, MINERÍA, ACTUALIDAD, ETC)
    private function __getPostData($objectType, $objectTypeName, $postSlug, $objectId) {
        $data = [];

        switch ($objectType) {
            case 'post-type':
                switch ($objectTypeName) {
                    case 'example':
                        $data = ['example' => 'Hi, From Panda WP'];
                        break;
                }
                break;

            case 'page':
                switch ($postSlug) {
                case 'home': // <--- Este es el caso para tu Portada
                    $heroPost = Timber::get_post([
                        'post_type' => 'post',
                        'meta_query' => [['key' => 'es_hero', 'value' => '1']],
                        'posts_per_page' => 1
                    ]);

                    if ($heroPost) {
                        $heroData = [
                            'id'            => $heroPost->ID,
                            'title_home'         => $heroPost->titulo_portada(),
                            'post_excerpt'  => $heroPost->post_excerpt,
                            'hero_image'    => $heroPost->thumbnail() ? $heroPost->thumbnail()->src() : null,
                            'category_name' => (count($heroPost->terms())) ? $heroPost->terms()[0]->name : 'Actualidad'
                        ];
                    }
                    // Obtenemos las destacadas
                    $destacadasPosts = Timber::get_posts([
                        'post_type' => 'post',
                        'meta_query' => [['key' => 'es_destacada', 'value' => '1']],
                        'posts_per_page' => 3
                    ]);
                    // Preparamos cada destacada para que no le falte imagen ni categoría
                    $destacadasData = [];
                    foreach ($destacadasPosts as $p) {
                        $p_terms = $p->terms();
                        
                        $destacadasData[] = [
                            'id'            => $p->ID,
                            'title_home'         => $p->titulo_portada(),
                            'post_excerpt'  => $p->post_excerpt,
                            'category_name' => ($p_terms && !empty($p_terms)) ? $p_terms[0]->name : 'Destacado',
                            'url'           => $p->link() // Si prefieres enviar la URL procesada desde PHP
                        ];
                    }

                    $data = [
                        'hero' => $heroData,
                        'destacadas' => $destacadasData
                    ];
                    break;

                case 'example': // Tu caso anterior se mantiene
                    $data = ['articles' => Timber::get_posts(['post_type' => 'post'])];
                    break;
            }
                break;
        }

        return $data;
    }
    
    // LÓGICA PARA IDENTIFICAR TIPOS DE ARTICULOS Y AGRUPARLOS (TECNOLOGÍA, MINERÍA, ACTUALIDAD, ETC)
    private function __getTermData($objectTypeName, $postSlug, $objectId, $parent) {
        $data = [];

        switch ($objectTypeName) {
            case 'example':
                if ($parent) {
                    /* subcategory */
                } else {
                    /* category */
                }

                $data = ['example' => 'Hi, From Panda WP'];
                break;
        }

        return $data;
    }

    // TRAE LO QUE NO CAMBIA ENTRE PÁGINAS. MENÚ PRINCIPAL, MENÚ DE FOOTERM LOGO, REDES SOCIALES, ETC
    //INICIALIZA LA APLICACIÓN VUE
    private function __getGeneralData() {
        $primaryMenu    = Timber::get_menu('primary-menu');
        $footerMenu     = Timber::get_menu('footer-menu');

        if (isset($primaryMenu->items)) {
            $primaryMenu->items = array_map(function($item) {
                $item->url  = $item->path();
                $item->name = $item->title();

                return $item;
            }, $primaryMenu->items);
        }

        if (isset($footerMenu->items)) {
            $footerMenu->items = array_map(function($item) {
                $item->url  = $item->path();
                $item->name = $item->title();

                return $item;
            }, $footerMenu->items);
        }

        return [
            'information' => (object)[
                /* ACF queries */
                // "phone" => get_field('phone', 'options'),
                // "email" => get_field('email', 'options')
            ],
            'primary_menu'  => $primaryMenu->items ?? [],
            'footer_menu'   => $footerMenu->items ?? []
        ];
    }
}
