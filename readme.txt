=== Plugin Name ===
Contributors: viniciusandre
Donate link: http://vinicius.soylocoporti.org.br
Tags: images, convert, phpthumb, gd
Requires at least: 2.9.2
Tested up to: 3.0.1
Stable tag: 0.02

Get Post Image is a wrapper for Get The Image WordPress Plugin and phpThumb library. It manages to easily get and convert an images from posts.

== Description ==

Get Post Image is a wrapper for Get The Image WordPress Plugin and phpThumb library. It manages to easily get and convert an image from a post, and can be used for thumbnailing, formatting, masks, logo insertion and a lot of other operations related to images.

Visit http://vinicius.soylocoporti.org.br/get-post-image-wordpress-plugin/ for usage and more information.

== Installation ==

1. Download and activate the Get The Image Plugin.
2. Download and activate the Get Post Image Plugin.
3. Open the file get-post-image/get-post-image.php and change the GPI_HIGHT_SECURITY_PASSWORD to watever password you want. This won’t let bad intentioned people overload your server creating tons of random images.

Visit http://vinicius.soylocoporti.org.br/get-post-image-wordpress-plugin/ for more information.

== Frequently Asked Questions ==

Visit http://vinicius.soylocoporti.org.br/get-post-image-wordpress-plugin/ to see and ask questions.

== Screenshots ==

Visit http://vinicius.soylocoporti.org.br/get-post-image-wordpress-plugin/ for live examples.

== Changelog ==

= 0.02 =

* Changed the way of calling the plugin, now it's done via an array of options. See the documentation for more info;
* Now it works with multisite. There's no absolute way of make it work. Some servers and websites will need a different parse to get the images path;
* There's a verification to check if "get-the-image" is also installed.

= 0.01 =
Plugin released. Don't expect multisite to work.
