<?php
/*
Plugin Name: Parallel Load
Plugin URI: http://blog.mudy.info/my-plugins/
Description: Load Javascript files in parallel. Additional settings can be found in <a href="options-misc.php">Miscellaneous</a>.
Version: 1.5
Author: Yejun Yang
Author URI: http://blog.mudy.info/
*/

/*  Copyright 2009 Yejun Yang  (email : yejunx At gmail Dot com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function parallel_print_scripts( $handles =false) {
	global $wpcf7;

        if ( '' === $handles ) // for wp_head
                $handles = false;
        global $wp_scripts;
        if ( !is_a($wp_scripts, 'WP_Scripts') ) {
                        $wp_scripts = new WP_Scripts();
        }
	$handles = false === $handles ? $wp_scripts->queue : (array) $handles;
                $wp_scripts->all_deps( $handles );
		foreach( $wp_scripts->to_do as $handle ) {
			$wp_scripts->print_scripts_l10n( $handle );
		}
                echo '<!-- Parallel Load Start | http://wordpress.org/extend/plugins/parallel-load/ --><script type="text/javascript">';
                foreach( $wp_scripts->to_do as $handle ) {
                        if ( !in_array($handle, $wp_scripts->done) && isset($wp_scripts->registered[$handle]) ) {
                                if ( $wp_scripts->registered[$handle]->src ) {
                                        $src = $wp_scripts->registered[$handle]->src;
                			if ( !preg_match('|^https?://|', $src) && !preg_match('|^' . preg_quote(WP_CONTENT_URL) . '|', $src)) {
                        			$src = $wp_scripts->base_url . $src;
					}

                			$src = clean_url(apply_filters( 'script_loader_src', $src, $handle ));

?>
document.write(unescape("%3Cscript src=\"<?php echo $src ?>\" type=\"text/javascript\"%3E%3C/script%3E"));
<?php      
                                }
                                $wp_scripts->done[] = $handle;
                        }
                }
                echo '</script>';
                $wp_scripts->to_do = array();
		if (isset($wpcf7)) {
			echo '<!-- Contact Form 7 Fix -->';
			$wpcf7->wp_head();
		}
		if (function_exists('mfbfw_init')) {
			mfbfw_init();
		}
                return $wp_scripts->done;        
}


add_option('parallel_load_bottom');

function parallel_load_api_init() {
	add_settings_section('parallel_load_section', 'Parallel Load', 'parallel_load_section_callback_function', 'misc');
	add_settings_field('parallel_load_bottom', 'Bottom Loading (optional)', 'parallel_load_bottom_callback_function', 'misc', 'parallel_load_section');
	register_setting('misc','parallel_load_bottom');
}

add_action('admin_init', 'parallel_load_api_init');

function parallel_load_section_callback_function() {

}


function parallel_load_bottom_callback_function() {
	$checked = '';
	
	if (get_option('parallel_load_bottom') == 'true') 
		$checked = " checked='checked' ";

?>
<label for="parallel_load_bottom">
<input <?php echo $checked; ?> name="parallel_load_bottom" id="parallel_load_bottom" type="checkbox"
value="true" /> Load javascripts at bottom of html instead of head. Not all ajax plugins are compatible with this option.
</label>
<?php
} 

function parallel_load_fix() {
	global $wpcf7;
	if (isset($wpcf7)) {
		remove_action('wp_head', array(&$wpcf7, 'wp_head'));
	}
	if (function_exists('mfbfw_init')) {
		remove_action('wp_head','mfbfw_init');
	}
	do_action( 'wp_print_scripts' );
}

if (!is_admin()){
remove_action('wp_head', 'wp_print_scripts');
add_action('wp_head', 'parallel_load_fix', 0);
if (get_option('parallel_load_bottom') == 'true')
	add_action( 'wp_footer', 'parallel_print_scripts' , 0);
else
	add_action( 'wp_head', 'parallel_print_scripts' );
}
?>
