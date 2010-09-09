<?php
/*
Plugin Name: Get Post Image
Version: 0.01
Description: Get Post Image is a wrapper for Get The Image Plugin and phpThumb library. It manages to easily get and convert an image from a post, and can be used for thumbnailing, formatting, masks, logo insertion and a lot of other operations related to images.
Author: Vinicius Massuchetto
Plugin URI: http://wordpress.org/extend/plugins/get-post-image/
*/

/* Settings */

define ('GPI_HIGHT_SECURITY_PASSWORD', 'anypasswordhere');

/* Code */

register_activation_hook (__FILE__, 'gpi_init');
function gpi_init () {
	if (!function_exists('get_the_image')) {
		deactivate_plugins ('get-post-image/get-post-image.php');
		wp_die(__('The "<a href="http://wordpress.org/extend/plugins/get-the-image/">Get the Image</a>" plugin is required but is not installed. Please install it before activating "Get Post Image".<br/><br/>Go back <a href="javascript:history.back()">here</a>.'));
	}
}

function get_post_image ($args = false, $mode = 'img', $img = false) {
	global $post;

	if (!$img) {
		if (!$options)
			$options = array (
				'image_scan' => true,
				'echo' => false,
				'format' => 'array',
				'default_size' => 'full',
				'default_image' => NO_IMAGE
			);
		$img = get_the_image ($options);
	}

	$img = ($img['url']) ? $img['url'] : $img;
	if (!$args) return $img;
	$pt = get_phpthumb ($args, $img);

	if ($mode == 'url') return $pt;
	if ($mode == 'img')
		echo '<img src="'.$pt.'" alt="'.$post->post_name.'" />';
	return;
}

function get_phpthumb ($args, $img) {
	require_once WP_PLUGIN_DIR.'/get-post-image/phpthumb/phpThumb.config.php';
	$phpthumb = WP_PLUGIN_URL.'/get-post-image/phpthumb/phpThumb.php';

	$PHPTHUMB_CONFIG['high_security_password'] = GPI_HIGHT_SECURITY_PASSWORD;

	$img = url2path ($img);
	$phpthumb .= '?src='.urlencode($img).'&'.$args;
	$hash = md5 ($phpthumb.$PHPTHUMB_CONFIG['high_security_password']);
	$phpthumb .= '&hash='.$hash;
	return $phpthumb;
}

function url2path ($url) {
	global $blog_id;

	$url = str_replace ('www.', '', $url);

	// TODO: Multisite Support
	if (function_exists('is_multisite')) {
		if (is_multisite()) {
			$url = str_replace ('files/', '', $url);
			$url = str_replace (get_bloginfo('url').'/', '', $url);
			$blogname = explode ('/', $url);
			$blogname = $blogname[0];
			$id = get_id_from_blogname ($blogname);
			if (!$blog_id) $blog_id = 1;
			else $url = str_replace ($blogname.'/', '', $url);
			return ABSPATH.UPLOADBLOGSDIR.'/'.$blog_id.'/files/'.$url;
		}
	}

	return str_replace (get_bloginfo('url').'/', ABSPATH, $url);
}

?>
