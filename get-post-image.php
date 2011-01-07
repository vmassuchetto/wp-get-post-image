<?php
/*
Plugin Name: Get Post Image
Version: 0.03
Description: Get Post Image is a wrapper for Get The Image Plugin and phpThumb library. It manages to easily get and convert an image from a post, and can be used for thumbnailing, formatting, masks, logo insertion and a lot of other operations related to images.
Author: Vinicius Massuchetto
Plugin URI: http://wordpress.org/extend/plugins/get-post-image/
*/

/* Settings */

define ('GPI_HIGHT_SECURITY_PASSWORD', 'anypasswordhere');
define ('GPI_DEFAULT_IMAGE', 'your-img-url-here');

/* Code */

/**
 * @brief Get formatted image from posts
 * @param $args Array of options. See comments.
 */
function get_post_image ($args) {
	global $post;
    
    if (!function_exists('get_the_image')) {
        _e('The "<a href="http://wordpress.org/extend/plugins/get-the-image/">Get the Image</a>" plugin is required but is not installed. Please install it before activating "Get Post Image".<br/><br/>Go back <a href="javascript:history.back()">here</a>.');
        return;
    }

	if (!$args)
		return false;

	if (is_string ($args)) {
		$args = array (
			'phpthumb' => $args,
			'echo' => true
		);
	}
	
	$defaults = array (
		'phpthumb'      => false,  // phpThumb arguments to use. See http://phpthumb.sourceforge.net/demo/docs/phpthumb.readme.txt
                                   // Will return the original full size image if not set.
		'default_image' => false,  // Will use this URL if nothing is found.
		'image'         => false,  // Do not search for images, get the phpThumb string for this URL instead.
		'post_id'       => false,  // Get image from specific post ID, not the current post in the loop. Preceded by the 'image' option.
		'echo'          => true,   // Will echo a complete HTML image tag if true, or will just return the img's URL if false.
		'extra'			=> false   // Extra attributes for your img object like. Will remove the default "alt" and put the ones specified.
	);

	$args = array_merge ($defaults, $args);
	
	if (!$args['default_image'] && GPI_DEFAULT_IMAGE)
		$args['default_image'] = GPI_DEFAULT_IMAGE;

	if ($args['post_id'] && ($post = get_post ($args['post_id'])))
		setup_postdata ($post);

	if (!$args['image']) {
		$options = array (
			'image_scan' => true,
			'echo' => false,
			'format' => 'array',
			'default_size' => 'full',
			'default_image' => $args['default_image']
		);
		$img = get_the_image ($options);
	}
	$img = ($img['url']) ? $img['url'] : $args['img'];
	
	if (!$args['phpthumb'])
		return $img;
	
	$img = get_phpthumb ($args['phpthumb'], $img);

	if (!$img && is_multisite())
		$img = get_user_avatar ($args['phpthumb'], $post->post_author);
	
	if ($args['echo']) {
		$extra = ($args['extra']) ? $args['extra'] : 'alt="'.$post->post_title.'"';
		echo '<img src="'.$img.'" '.$extra.' />';
	} else
		return $img;

}

function get_phpthumb ($args, $img, $url2path = true) {
	global $post;
	require_once WP_PLUGIN_DIR.'/get-post-image/phpthumb/phpThumb.config.php';
	$phpthumb = WP_PLUGIN_URL.'/get-post-image/phpthumb/phpThumb.php';
	$PHPTHUMB_CONFIG['high_security_password'] = GPI_HIGHT_SECURITY_PASSWORD;

	if ($url2path)
		$img = url2path ($img);

	if (@!is_file($img)) 
		$img = GPI_DEFAULT_IMAGE;

	$phpthumb .= '?src='.urlencode($img).'&'.$args;
	$hash = md5 ($phpthumb.$PHPTHUMB_CONFIG['high_security_password']);
	$phpthumb .= '&hash='.$hash;
	return $phpthumb;
}

function url2path ($url) {

	if (!is_string($url))
		return false;
		
	$url = parse_url ($url);

	if (is_multisite()) {
		global $blog_id;
		$upload = wp_upload_dir();
		$upload['basedir'] = addslashes($upload['basedir']);
		$path = preg_filter ('/.*\/files\//', $upload['basedir'].'/', $url['path'], -1, $replaced);
		if ($replaced)
			return $path;
		else
			return preg_replace ('/.*wp-content/', ABSPATH.'/wp-content', $url['path']);	
	}

	return $_SERVER['DOCUMENT_ROOT'].$url['path'];
}

function get_user_avatar ($args, $user) {
	
	if (!is_int($user))
		$id = get_user_id_from_string ($user);
	else
		$id = $user;

	$avatar_dir = ABSPATH.'wp-content/blogs.dir/1/files/avatars/'.$id.'/';
	$img = GPI_DEFAULT_IMAGE;
	if (@$dir = opendir ($avatar_dir)) {
		while ($f = readdir($dir)) {
			if (strpos ($f, 'bpfull') !== false) {
				$img = $avatar_dir.$f;
				break;
			}
		}
	}
	
	return get_phpthumb ($args, $img, false);
}

?>
