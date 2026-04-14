<?php
error_log("🚀 PANDA SYSTEM ONLINE: El tema está activo y el log funciona.");

require_once(__DIR__ . '/config/bootstrap.php');
Bootstrap::config();

/**
 * Permitir que el frontend (Vite/Vue) acceda a la API de WordPress
 */
add_action('init', function() {
    // Esto le dice a WordPress que acepte peticiones desde tu puerto 3000
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce");
    
    // Si el navegador pregunta "¿puedo pasar?" (petición OPTIONS), respondemos que sí
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (function_exists('status_header')) {
            status_header(200);
        }
        exit;
    }
});

add_action('admin_footer', function() {
    ?>
    <script>
    (function() {
        // Límite físico de caracteres
        const CHAR_LIMIT = 200; 

        const applyLimit = () => {
            // Buscamos la caja de texto del extracto (Gutenberg usa un textarea o un div editable)
            const excerptField = document.querySelector('.editor-post-excerpt textarea');
            
            if (excerptField && !excerptField.dataset.limitSet) {
                excerptField.setAttribute('maxlength', CHAR_LIMIT);
                excerptField.dataset.limitSet = 'true'; // Para no repetir el proceso
                
                // Bloqueo manual para el "Pegar" (Paste)
                excerptField.addEventListener('input', (e) => {
                    if (e.target.value.length > CHAR_LIMIT) {
                        e.target.value = e.target.value.substring(0, CHAR_LIMIT);
                    }
                });
            }
        };

        // Como Gutenberg carga componentes dinámicamente, vigilamos el DOM
        const observer = new MutationObserver(() => {
            applyLimit();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    })();
    </script>
    <?php
});