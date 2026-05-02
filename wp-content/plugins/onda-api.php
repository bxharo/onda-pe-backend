<?php
/*
Plugin Name: Onda API Engine
Description: Migración de PageController a Custom API para Nuxt
Version: 2.0
*/
$composer_autoload = dirname(__DIR__, 2) . '/vendor/autoload.php';

if ( file_exists( $composer_autoload ) ) {
    require_once $composer_autoload;
}

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/pages(?:/(?P<slug>[a-zA-Z0-9-]+))?', [
        'methods'  => 'GET',
        'callback' => 'handle_onda_page_request',
        'args' => [
            'slug' => [
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field'
            ],
        ],
    ]);
});

function handle_onda_page_request($request) {
    $param_slug = $request->get_param('slug');
    $url_slug   = $request['slug'];

    // Prioridad: 1. Parámetro ?slug= 2. Atributo de ruta 3. Default 'home'
    $slug = $param_slug ?: ($url_slug ?: 'home');

    $type     = $request->get_param('type');
    $typeName = $request->get_param('type-name')?: $request->get_param('typeName');

    $pageData = null;

    switch ($type) {
        case 'general':
            $pageData = get_onda_general_data();
            break;

        case 'term':
            $pageData = get_onda_term_data($typeName ?: 'category', $slug);
            break;

        case 'page':
        case 'post-type':
            // 1. CASO ESPECIAL: PORTADA
            // Si el slug es home o vacío, llamamos al especialista de Home
            if ($slug === 'home' || $slug === 'general' || empty($slug)) {
                $pageData = get_onda_home_data();
            } 
            else {
                // 2. CASO: ARTÍCULOS O PÁGINAS INDIVIDUALES
                // Primero obtenemos el objeto de Timber
                $post_obj = \Timber\Timber::get_post([
                    'post_type' => $typeName ?: 'any', 
                    'name'      => $slug
                ]);

                if ($post_obj) {
                    // Llamamos al especialista de Single Post pasando el objeto
                    $pageData = get_onda_single_post_data($post_obj);
                }
            }
            break;
    }

    if ($pageData) {
        return new WP_REST_Response($pageData, 200);
    }

    return new WP_Error('no_content', 'No se encontró contenido', ['status' => 404]);
}

// 1. DATA GENERAL (MENÚS)
function get_onda_general_data() {
    $primaryMenu = \Timber\Timber::get_menu('primary-menu');
    $footerMenu  = \Timber\Timber::get_menu('footer-menu');

    $formatMenu = function($menu) {
        if (!$menu || !isset($menu->items)) return [];
        return array_map(function($item) {
            $path = $item->path();
            // Mantenemos tu lógica de limpieza de rutas de GitHub
            $cleanPath = str_replace('/articulo/category/', '/categoria/', $path);
            return [
                'id'   => $item->id,
                'name' => $item->title(),
                'url'  => rtrim($cleanPath, '/'),
                'slug' => $item->slug
            ];
        }, $menu->items);
    };

    return [
        'information'  => (object)[],
        'primary_menu' => $formatMenu($primaryMenu),
        'footer_menu'  => $formatMenu($footerMenu)
    ];
}

// 2. DATA DE CATEGORÍAS (LISTADOS)
function get_onda_term_data($taxonomy, $slug) {
// 1. Buscamos el término de forma segura
    $term_obj = get_term_by('slug', $slug, $taxonomy);

    if (!$term_obj) {
        return [
            'status' => 'error',
            'message' => "No se encontró el slug '$slug'"
        ];
    }

    // 2. Cargamos el objeto Timber usando el ID que encontramos
    $term = \Timber\Timber::get_term($term_obj->term_id);

    // 3. Obtenemos los posts. 
    // Usamos 'category_name' como lo hacías antes si te funcionaba bien,
    // pero a través de Timber es más sólido:
    $posts = $term->posts([
        'post_type'      => 'post',
        'posts_per_page' => 12,
        'tax_query' => [
            [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $term->ID,
                'include_children' => false // <--- ESTO hace que solo salgan las categorías PADRE
            ]
        ]
    ]);

    return [
        'term_id'        => $term->ID,
        'category_title' => $term->name,
        'description'    => $term->description,
        'articles'       => array_map(function($p) {
            return [
                'id'      => $p->ID,
                'title'   => $p->title(),
                'excerpt' => wp_strip_all_tags($p->post_excerpt),
                'image'   => $p->thumbnail() ? $p->thumbnail()->src() : null,
                'url'     => $p->path(),
                'date'    => $p->date('d M, Y')
            ];
        }, $posts->to_array())
    ];
}

// 3. ESPECIALISTA EN PORTADA
function get_onda_home_data() {
    
    $data = [
        'is_home' => true,
        'post'    => ['title' => 'Portada'] // Data mínima
    ];

        // --- HERO ---
        $heroPost = \Timber\Timber::get_post([
            'post_type'      => 'post',
            'meta_query'     => [['key' => 'es_hero', 'value' => '1']],
            'posts_per_page' => 1
        ]);

        if ($heroPost) {
            $data['hero'] = [
                'id'            => $heroPost->ID,
                'title_home'    => $heroPost->title(),
                'post_excerpt'  => wp_strip_all_tags($heroPost->post_excerpt), // Usamos excerpt() para asegurar contenido
                'hero_image'    => $heroPost->thumbnail() ? $heroPost->thumbnail()->src() : null,
                'category_name' => (count($heroPost->terms())) ? $heroPost->terms()[0]->name : 'Actualidad',
                'url'           => $heroPost->path()
            ];
        }

        // --- DESTACADAS ---
        $destacadasPosts = \Timber\Timber::get_posts([
            'post_type'      => 'post',
            'posts_per_page' => 9,
            'meta_query'     => [['key' => 'es_destacada', 'value' => '1']],
            'orderby'        => 'menu_order',
            'order'          => 'ASC'
        ]);

        $destacadasArray = $destacadasPosts->to_array();
        $data['destacadas'] = array_map(function($p) {
            return [
                'id'         => $p->ID,
                'title_home' => $p->title(),
                'excerpt'    => wp_strip_all_tags($p->post_excerpt), // Cambiado para que coincida con el nombre en Nuxt
                'image'      => $p->thumbnail() ? $p->thumbnail()->src() : null,
                'url'        => $p->path(),
                'category_name' => (count($p->terms())) ? $p->terms()[0]->name : 'Destacado'
            ];
        }, $destacadasArray);

        // --- SECCIONES DE CATEGORÍAS (DINÁMICO) ---
        // Preparamos IDs para excluir lo que ya salió en Hero y Destacadas
        $exclude_ids = $heroPost ? [$heroPost->ID] : [];
        foreach ($destacadasArray as $p) { $exclude_ids[] = $p->ID; }

        $categories = \Timber\Timber::get_terms([
            'taxonomy'   => 'category',
            'hide_empty' => true,
            'parent'     => 0,
            'exclude'    => [1], // Excluir 'Sin categoría'
        ]);

        $sectionsData = [];
        foreach ($categories as $cat) {
            $cat_posts = \Timber\Timber::get_posts([
                'post_type'      => 'post',
                'category_name'  => $cat->slug,
                'posts_per_page' => 4,
                'post__not_in'   => $exclude_ids,
            ]);
        
            if (!empty($cat_posts)) {
                $sectionsData[] = [
                    'category_name' => $cat->name,
                    'category_slug' => $cat->slug,
                    'articles'      => array_map(function($p) use ($cat) {
                        return [
                            'id'      => $p->ID,
                            'title'   => $p->title(),
                            'excerpt' => wp_strip_all_tags($p->post_excerpt),
                            'image'   => $p->thumbnail() ? $p->thumbnail()->src() : null,
                            'url'     => $p->path()
                        ];
                    }, $cat_posts->to_array())
                ];
            }
        }
        
        $data['category_sections'] = $sectionsData;
    
    
    return $data;
}

// 4. ESPECIALISTA EN CONTENIDO INDIVIDUAL
function get_onda_single_post_data($post_obj) {
    if (!$post_obj) return null;
    
    // 1. Estructura de la data principal
    $data = [
        'post' => [
            'id'             => $post_obj->ID,
            'title'          => $post_obj->title(),
            'excerpt'        => wp_strip_all_tags($post_obj->post_excerpt),
            'content'        => $post_obj->content(),
            'featured_image' => $post_obj->thumbnail() ? $post_obj->thumbnail()->src() : null,
            'author_name'    => $post_obj->author() ? $post_obj->author()->name() : 'Redacción Onda',
            'date'           => $post_obj->date('c'), // Formato ISO 8601 (perfecto para JS)
            'category_name'  => (count($post_obj->terms())) ? $post_obj->terms()[0]->name : 'General'
        ],
        'related' => []
    ];

    // Lógica de RELACIONADOS (solo si es un post individual y no la home)
        $tag_ids = wp_get_post_tags($post_obj->ID, ['fields' => 'ids']);
        
        $related_query = [
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'post__not_in'   => [$post_obj->ID], // Excluir el actual
            'orderby'        => 'date',
            'order'          => 'DESC'
        ];
        
        // Prioridad: Tags > Categoría
        if (!empty($tag_ids)) {
            $related_query['tag__in'] = $tag_ids;
        } else {
            // Si no hay tags, usamos la categoría del post actual
            $terms = $post_obj->terms();
            if (!empty($terms)) {
                $related_query['category__in'] = [$terms[0]->ID];
            }
        }

        $related_posts = \Timber\Timber::get_posts($related_query);

        if($related_posts){
            $data['related'] = array_map(function($p) {
                return [
                    'id'      => $p->ID,
                    'title'   => $p->title(),
                    'excerpt' => wp_strip_all_tags($p->post_excerpt),
                    'image'   => $p->thumbnail() ? $p->thumbnail()->src() : null,
                    'category_name' => (count($p->terms())) ? $p->terms()[0]->name : 'General',
                    'url'     => $p->path(),
                    'date'    => $p->date('d M, Y')
                ];
            }, $related_posts->to_array());
        }
    

    return $data;

}