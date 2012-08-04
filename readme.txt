=== Get Post Image ===

Contributors: viniciusmassuchetto
Donate link: http://vinicius.soylocoporti.org.br
Tags: images, convert, phpthumb, gd
Requires at least: 2.9.2
Tested up to: 3.4.1
Stable tag: 0.05

Get Post Image allows you to easily get and convert an image from a post, it supports resizing, color corrections, masks and other advanced operations.

== Description ==

Get Post Image can easily resize, adjust, flip, and make a lot of operations in the uploaded images right into the code. Site visitors can't actually make HTTP requests to generate the images because phpThumb was adjusted to allow only WordPress to do it. The image will be saved in the same directory of the converted image and will never be processed again.

== Installation ==

1. Download and activate the Get Post Image Plugin.
2. Change the default password in the get-post-image-config.php file.

== Usage ==

For example, to display a post thumbnail in a 200x100 format, you can simply do:

<code><?php get_post_image('w=200&h=100'); ?></code>

Or as a shortcode in the post content:

<code>[get-post-image phpthumb="w=200&h=100"]</code>

If the picture is a little bit dark, you can increase brightness in 20% with:

<code><?php get_post_image('w=200&zc=1&fltr[]=brit|20'); ?></code>

And the shortcode:

<code>[get-post-image phpthumb="w=200&zc=1&fltr&#91;&#93;=brit|20"]</code>

There's many more options. Please visit <a href="http://vinicius.soylocoporti.org.br/get-post-image-wordpress-plugin/">the plugin page</a> for configuration, usage, examples and other information.

== Screenshots ==

1. This is the original image.
2. Resizing to 300x300px: `get_post_image ('w=300&h=300');`
3. Adjusting brightness: `get_post_image ('w=200&zc=1&fltr[]=brit|20');`
4. Flip vertically: `get_post_image ('w=200&zc=1&fltr[]=flip|y');`
5. Text: `get_post_image ('w=200&zc=1&fltr[]=wmt|I\\''m a Camel! &#169;|16|C|FFFFFF|/path/to/liberation-sans.ttf|100|10|0|000000|80|x');`
6. Watermak image: `get_post_image ('w=200&zc=1&fltr[]=wmi|/path/to/camel-logo.png|T|50|5|5|320');`
7. Applying mask: `get_post_image ('w=200&zc=1&fltr[]=mask|/path/to/camel-abstract.png&f=png');`
8. Mask applyed

== Changelog ==

= 0.05 =

* Removing get_the_image dependency
* Converting images on the server side
* Adding the security password
* Improving the calling method

= 0.04 =

* phpThumb update to fix security issues;
* Displaying nice error messages instead of breaking execution.

= 0.03 =

* Minor changes to make it work properly on Windows servers.

= 0.02 =

* Changed the way of calling the plugin, now it's done via an array of options. See the documentation for more info;
* Now it works with multisite. There's no absolute way of make it work. Some servers and websites will need a different parse to get the images path;
* There's a verification to check if "get-the-image" is also installed.

= 0.01 =

* Plugin released. Don't expect multisite to work.
