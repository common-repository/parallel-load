=== Plugin Name ===
Contributors: yejun
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3494945
Tags: javascript,AJAX,plugin
Requires at least: 2.7
Tested up to: 2.7.1
Stable tag: trunk

Load Javascript files in parallel by using document.write tag.

== Description ==

Load Javascript files in parallel by using document.write tag. 

* Optional bottom loading javascript can be enabled in Settings / Miscellaneous. If your plugin is compatible, this will improve perceivable page loading time.
* You can use [pagetest](http://webpagetest.org/) to test your result.
* Only script using wp_enqueue_script() will be loaded in parallel

Change Log:

* 1.5 Compatible with [FancyBox][] 1.5.1, please upgrade fancybox as well
* 1.4 Added [FancyBox][] support  Mar 21, 2009
* 1.3 Support l10n script
* 1.2 Added [Contact Form 7][] support
* 1.1 Fix a bug, hope it will have better plugin compatibility.

[FancyBox]: http://wordpress.org/extend/plugins/fancybox-for-wordpress/
[Contact Form 7]: http://wordpress.org/extend/plugins/contact-form-7/

== Installation ==

1. Upload `parallel.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. More settings can be found in Settings / Miscellaneous

== Screenshots ==

1. Pagetest with plugin
2. Pagetest without plugin
3. Settings
