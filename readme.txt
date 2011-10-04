=== Plugin Name ===
Contributors: viniciusandre
Donate link: http://vinicius.soylocoporti.org.br
Tags: images, convert, phpthumb, gd
Requires at least: 2.9.2
Tested up to: 3.1
Stable tag: 0.04

Get Post Image is a wrapper for Get The Image WordPress Plugin and phpThumb library. It manages to easily get and convert an images from posts.

== Description ==

Get Post Image is a wrapper for Get The Image WordPress Plugin and phpThumb library. It manages to easily get and convert an image from a post, and can be used for thumbnailing, formatting, masks, logo insertion and a lot of other operations related to images.

Please visit http://vinicius.soylocoporti.org.br/get-post-image-wordpress-plugin/ for usage and more information.

<b>Warning</b>: phpThumb bundled on Get Post Image versions <= 0.03 have a <a href="http://snipper.ru/view/8/phpthumb-179-arbitrary-command-execution-exploit/">severe security issue</a>. Don't use it.

== Installation ==

1. Download and activate the Get The Image Plugin.
2. Download and activate the Get Post Image Plugin.
3. Configure phpThumb on get-post-image/phpthumb

Visit <a href="http://vinicius.soylocoporti.org.br/get-post-image-wordpress-plugin/">the plugin page</a> for configuration, usage, examples and more information.

== Frequently Asked Questions ==

Please visit <a href="http://vinicius.soylocoporti.org.br/get-post-image-wordpress-plugin/">the plugin page</a>.

== Screenshots ==

Please visit <a href="http://vinicius.soylocoporti.org.br/get-post-image-wordpress-plugin/">the plugin page</a> for examples.

== Changelog ==

= 0.04 =

* phpThumb update to fix security issues.
* Displaying nice error messages instead of breaking execution.

= 0.03 =

* Minor changes to make it work properly on Windows servers

= 0.02 =

* Changed the way of calling the plugin, now it's done via an array of options. See the documentation for more info;
* Now it works with multisite. There's no absolute way of make it work. Some servers and websites will need a different parse to get the images path;
* There's a verification to check if "get-the-image" is also installed.

= 0.01 =
Plugin released. Don't expect multisite to work.
