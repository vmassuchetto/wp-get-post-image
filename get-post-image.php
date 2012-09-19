<?php
/*
 * Plugin Name: Get Post Image
 * Description: Get Post Image is a wrapper for the phpThumb library. It manages to easily get and convert an image from a post, and can be used for thumbnailing, formatting, masks, logo insertion and a lot of other operations.
 * Version: 0.07
 * Author: Vinicius Massuchetto
 * Author URI: http://vinicius.soylocoporti.org.br
 * Contributors: viniciusmassuchetto
 * Plugin URI: http://vinicius.soylocoporti.org.br/get-post-image-wordpress-plugin/
 */

add_theme_support ('post-thumbnails');
$gpi = array(
    'phpthumb_url' => plugins_url('get-post-image') . '/phpthumb/phpThumb.php',
    'phpthumb_default_args' => 'w=100&h=100&zc=1'
);

function get_post_image ($args = false) {

    global $post, $gpi;

    if (!$args)
        $args = $gpi['phpthumb_default_args'];

    if (is_string ($args) && empty($post->ID))
        return false;

	if (is_string ($args))
		$args = array (
			'phpthumb' => $args,
			'echo' => true
		);

	$defaults = array (
		'phpthumb' => $gpi['phpthumb_default_args'],
		'echo' => true,
        'post_id' => (!empty($post->ID)) ? $post->ID : false,
        'image_id' => false,
        'shortcode' => false,
        'size' => false,
        'default_image' => false,
        'class' => false,
        'alt' => false
	);
	$args = wp_parse_args($args, $defaults);

    if (!$args['image_id']) {
        if (!$args['image_id'] = gpi_find_image_id($args['post_id']))
            if ($args['default_image'])
                $img = $args['default_image'];
            else
                return false;
    } elseif (!$args['post_id'] && $args['image_id']) {
        if ($args['image_id'])
            $p = get_post($i = $args['image_id']);
        if (empty($p->post_parent))
            $args['post_id'] = $p->post_parent;
    }

    if ($args['shortcode'])
        $args['phpthumb'] = html_entity_decode($args['phpthumb']);

    if (!$img = gpi_get_phpthumb($args)) {
        if ($args['default_image'])
            $img = $args['default_image'];
        else
            return false;
    }

    if (!$args['echo'])
        return $img;

    $img_post = get_post($args['image_id']);

    $class = $args['class'];
    if (empty($class)) {
        $class = 'gpi-img';
        if (!empty($img_post->ID))
            $class .= ' gpi-img-' . $img_post->ID;
    }

    $alt = $args['alt'];
    if(empty($alt)) {
    	if (!empty($post->post_title))
            $alt = htmlspecialchars($post->post_title);
    }

    $output = '<img src="' . $img . '"';
    if (!empty($class))
        $output .= ' class="' . $class . '"';
    if (!empty($alt))
        $output .= ' alt="' . $alt . '"';
    $output .= ' />';
    echo $output;

    return true;
}

function gpi_find_image_id($post_id) {
    if (!$img_id = get_post_thumbnail_id ($post_id)) {
        $attachments = get_children(array(
            'post_parent' => $post_id,
            'post_type' => 'attachment',
            'numberposts' => 1,
            'post_mime_type' => 'image'
        ));
        if (is_array($attachments)) foreach ($attachments as $a)
            $img_id = $a->ID;
    }
    if ($img_id)
        return $img_id;
    return false;
}

function gpi_get_phpthumb ($args) {

    global $post, $gpi, $gpi_config;

    require_once(plugin_dir_path(__FILE__) . '/get-post-image-config.php');

    /* Parse phpThumb args */

    $args_phpthumb = explode('&', $args['phpthumb']);
    $args_array = array();
    foreach ($args_phpthumb as $arg) {
        $a = explode('=', $arg);
        $args_keys[$a[0]] = rawurlencode($a[1]);
        $args_array[] = $a[0] . '=' . rawurlencode($a[1]);;
    }

    /* Rebuild arguments due to params ordering */

    ksort($args_array);
    $args_phpthumb = implode('&', $args_array);
    $args_str = implode('', $args_array);
    $args_slug = preg_replace('/[^A-Za-z0-9]/', '', $args_str);

    /* Get image to be converted */

    $img_url = false;
    if ($args['size']) {
        $img_url = wp_get_attachment_image_src($args['image_id'], $args['size']);
        $img_url = (!is_wp_error($img_url)) ? $img_url[0] : false;
    }
    if (!$img_url) {
        $img_url = wp_get_attachment_image_src($args['image_id'], 'full');
        $img_url = (!is_wp_error($img_url)) ? $img_url[0] : false;
    }

    if (!$img_url || is_wp_error($img_url))
        return false;

    $img_dir = dirname(get_attached_file($args['image_id']));
    $img_file = $img_dir . '/' . basename($img_url);

    if (!is_file($img_file))
        return false;

    /* Get image destination */

    preg_match('/^(.*)\/(.*)\.(jpg|jpeg|png|gif)$/i', $img_file, $matches);
    $ext = (in_array('f', array_keys($args_keys))) ? $args_keys['f'] : $matches[3];
    $converted_file = $matches[1] . '/' . $matches[2] . '-gpi-' . $args_slug . '.' . $ext;

    /* Convert if not converted */

    if (!is_file($converted_file) || $gpi_config['debug']) {

        $query_string = 'src=' . $img_file . '&' . $args_phpthumb;
        $hash = md5($query_string . $gpi_config['security_password']);
        $query_url = $gpi['phpthumb_url'] . '?' . $query_string . '&hash=' . $hash;

        $img = wp_remote_get($query_url);
        if (200 == $img['response']['code']) {
            @file_put_contents($converted_file, $img['body'], LOCK_EX);
        }

    }

    /* Serve it */

    $converted_file_url = dirname($img_url) . '/' . $matches[2] . '-gpi-' . $args_slug . '.' . $ext;
    return $converted_file_url;
}

function gpi_convert($args) {

    global $gpi;
    require_once($gpi['phpthumb_url']);

    $output = $args['out'];
    unset($args['out']);

    $pt = new phpThumb();
    $pt->getimagesizeinfo = @GetImageSize($args['src']);

    foreach ($args as $k => $v)
        $pt->setParameter($k, $v);
    $pt->RenderToFile($output);
    print_r($pt->debugmessages);


}

/* Shortcode */

add_shortcode ('get-post-image', 'get_post_image_shortcode');
function get_post_image_shortcode($args) {

    if (is_array($args))
        $args['shortcode'] = true;
    else
        $args = array('shortcode' => true);

    $args['echo'] = true;

    get_post_image($args);
}


?>
