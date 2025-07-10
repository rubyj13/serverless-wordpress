<?php
/**
 * Plugin Name: Video Grabber
 * Description: Un plugin para copiar automáticamente información de videos desde Pornhub y Xvideos.
 * Version: 1.13
 * Author: Tu Nombre
 */

defined('ABSPATH') or die('¡Sin acceso directo!');

function fetch_video_data($url) {
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);

    // Verificar si la URL es de Pornhub
    if (strpos($url, 'pornhub.com') !== false) {
        preg_match('/<title>(.*?)<\/title>/', $body, $matches);
        $title = isset($matches[1]) ? sanitize_text_field($matches[1]) : 'Título no encontrado';

        preg_match('/viewkey=([a-zA-Z0-9]+)/', $url, $id_matches);
        $video_id = isset($id_matches[1]) ? esc_attr($id_matches[1]) : '';

        $video_url = 'https://www.pornhub.com/embed/' . $video_id;

        preg_match('/<meta property="og:image" content="(.*?)"/', $body, $image_matches);
        $featured_image_url = isset($image_matches[1]) ? esc_url($image_matches[1]) : '';

        preg_match_all('/<meta property="video:tag" content="(.*?)"/', $body, $tag_matches);
        $tags = isset($tag_matches[1]) ? array_map('sanitize_text_field', array_unique($tag_matches[1])) : [];

    // Verificar si la URL es de Xvideos
    } elseif (strpos($url, 'xvideos.com') !== false || strpos($url, 'xvideos.es') !== false) {
        preg_match('/<title>(.*?)<\/title>/', $body, $matches);
        $title = isset($matches[1]) ? sanitize_text_field($matches[1]) : 'Título no encontrado';

        // Extraer el ID del video desde la URL
        preg_match('/video\.([a-zA-Z0-9]+)/', $url, $id_matches);
        $video_id = isset($id_matches[1]) ? esc_attr($id_matches[1]) : '';

        // Construir la URL del video para el embed
        $video_url = 'https://www.xvideos.com/embedframe/' . $video_id;

        preg_match('/<meta property="og:image" content="(.*?)"/', $body, $image_matches);
        $featured_image_url = isset($image_matches[1]) ? esc_url($image_matches[1]) : '';

        preg_match_all('/<meta name="keywords" content="(.*?)"/', $body, $tag_matches);
        // Separa las etiquetas por comas y limpia los espacios
        $tags = isset($tag_matches[1]) ? array_map('sanitize_text_field', explode(',', trim($tag_matches[1][0]))) : [];
        
    } else {
        return false; // Si la URL no es válida
    }

    return [
        'title' => $title,
        'video_url' => $video_url,
        'featured_image' => $featured_image_url,
        'tags' => $tags,
    ];
}

function ensure_category_exists($category_name) {
    if (!term_exists($category_name, 'category')) {
        wp_insert_term($category_name, 'category');
    }
}

function video_grabber_menu() {
    add_menu_page('Video Grabber', 'Video Grabber', 'manage_options', 'video-grabber', 'video_grabber_page');
}

add_action('admin_menu', 'video_grabber_menu');

function video_grabber_page() {
    ?>
    <div class="wrap">
        <h1>Video Grabber</h1>
        
        <!-- Formulario para agregar URL del video -->
        <p>Agrega la URL del video en el formulario de abajo.</p>
        <form method="post" action="">
            <?php wp_nonce_field('video_grabber_nonce_action', 'video_grabber_nonce'); ?>
            <label for="video_url">URL del Video:</label>
            <input type="text" id="video_url" name="video_url" style="width: 100%;" required>
            <button type="submit" class="button button-primary">Guardar Video</button>
        </form>

        <!-- Formulario para agregar script personalizado -->
        <h2>Configuración de Script Personalizado</h2>
        <form method="post" action="">
            <?php wp_nonce_field('custom_script_nonce_action', 'custom_script_nonce'); ?>
            <label for="custom_script">Script Personalizado:</label>
            <textarea id="custom_script" name="custom_script" rows="10" style="width: 100%;"><?php echo esc_textarea(get_option('video_grabber_custom_script')); ?></textarea>
            <button type="submit" class="button button-secondary">Guardar Script</button>
        </form>

        <!-- Opción para publicar directamente o guardar como borrador -->
        <h2>Configuración de Publicación</h2>
        <form method="post" action="">
            <?php wp_nonce_field('publish_option_nonce_action', 'publish_option_nonce'); ?>
            <label for="publish_option">Selecciona cómo guardar los videos:</label><br>
            <select id="publish_option" name="publish_option">
                <option value="draft" <?php selected(get_option('video_grabber_publish_option'), 'draft'); ?>>Guardar como Borrador</option>
                <option value="publish" <?php selected(get_option('video_grabber_publish_option'), 'publish'); ?>>Publicar Directamente</option>
            </select>
            <button type="submit" class="button button-secondary">Guardar Opción</button>
        </form>

        <?php 
            // Procesar la URL enviada al formulario de video
            if (isset($_POST['video_url'])) {
                if (!isset($_POST['video_grabber_nonce']) || !wp_verify_nonce($_POST['video_grabber_nonce'], 'video_grabber_nonce_action')) {
                    die('¡Nonce no válido!');
                }

                // Procesar la URL enviada al formulario
                $video_url = sanitize_text_field($_POST['video_url']);
                
                if (filter_var($video_url, FILTER_VALIDATE_URL) && (strpos($video_url, 'pornhub.com') !== false || strpos($video_url, 'xvideos.com') !== false || strpos($video_url, 'xvideos.es') !== false)) {
                    // Obtener los datos del video
                    $data = fetch_video_data($video_url);
                    if ($data) {
                        ensure_category_exists('Videos');
                        $category_id = get_cat_ID('Videos');

                        // Obtener la opción de publicación
                        $post_status = get_option('video_grabber_publish_option', 'draft');

                        // Crear un nuevo post con los datos obtenidos
                        $post_data = [
                            'post_title'   => sanitize_text_field($data['title']),
                            'post_content' => '<iframe src="' . esc_url($data['video_url']) . '" frameborder="0" width="800" height="600" scrolling="no" allowfullscreen></iframe>',
                            'post_status'  => $post_status,
                            'post_category'=> [$category_id]
                        ];
                        $new_post_id = wp_insert_post($post_data);

                        // Establecer la imagen destacada si se encontró una imagen
                        if ($data['featured_image']) {
                            set_post_thumbnail($new_post_id, media_sideload_image($data['featured_image'], $new_post_id, null, 'id'));
                        }

                        // Añadir tags automáticamente usando las categorías extraídas
                        if (!empty($data['tags'])) {
                            wp_set_post_tags($new_post_id, implode(',', array_unique($data['tags'])));
                        }

                        echo '<div class="updated"><p>Video guardado como '. esc_html(ucfirst($post_status)) .'. <a href="' . get_edit_post_link($new_post_id) . '">Editar Video</a></p></div>';
                    } else {
                        echo '<div class="error"><p>Error al obtener datos del video.</p></div>';
                    }
                } else {
                    echo '<div class="error"><p>URL inválida. Asegúrate de que sea una URL válida de Pornhub o Xvideos.</p></div>';
                }
            }

            // Procesar el script personalizado enviado
            if (isset($_POST['custom_script'])) {
                if (!isset($_POST['custom_script_nonce']) || !wp_verify_nonce($_POST['custom_script_nonce'], 'custom_script_nonce_action')) {
                    die('¡Nonce no válido!');
                }

                // Guardar el script personalizado en las opciones de WordPress
                update_option('video_grabber_custom_script', stripslashes($_POST['custom_script']));

                echo '<div class="updated"><p>Script personalizado guardado.</p></div>';
            }

            // Procesar la opción de publicación enviada
            if (isset($_POST['publish_option'])) {
                if (!isset($_POST['publish_option_nonce']) || !wp_verify_nonce($_POST['publish_option_nonce'], 'publish_option_nonce_action')) {
                    die('¡Nonce no válido!');
                }

                // Guardar la opción de publicación en las opciones de WordPress
                update_option('video_grabber_publish_option', sanitize_text_field($_POST['publish_option']));

                echo '<div class="updated"><p>Opción de publicación guardada.</p></div>';
            }
        ?>
    </div>
    <?php
}

// Hook para mostrar el script debajo del contenido del post
function display_custom_script() {
    echo get_option('video_grabber_custom_script');
}

add_action('the_content', function ($content) {
    if (is_single() && in_the_loop() && is_main_query()) { 
        return $content . display_custom_script();
    }
    return $content;
});
