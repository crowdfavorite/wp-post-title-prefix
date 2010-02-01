<?php
/*
Plugin Name: CF Post Title Prefix 
Plugin URI: http://crowdfavorite.com/wordpress/ 
Description: Custom field for post title prefix. Automatically added to title in RSS feeds, template tag for showing in theme. 
Version: 1.0 
Author: Crowd Favorite
Author URI: http://crowdfavorite.com
*/

// ini_set('display_errors', '1'); ini_set('error_reporting', E_ALL);

load_plugin_textdomain('post-title-prefix');

function cfptp_text($return = false) {
	global $post;
	$text = get_post_meta($post->ID, '_cfpfp_text', true);
	if ($return) {
		return $text;
	}
	else {
		echo $text;
	}
}

function cftpt_auto_prefix($post_title) {
	global $post;
	$text = get_post_meta($post->ID, '_cfpfp_text', true);
	if (!empty($text)) {
		$post_title = $text.': '.$post_title;
	}
	return $post_title;
}
add_filter('the_title_rss', 'cftpt_auto_prefix');

function cfptp_save_post($post_id) {
	if (current_user_can('publish_posts') && $_POST['cfptp_text_present']) {
		if (!update_post_meta($post_id, '_cfpfp_text', $_POST['cfptp_text'])) {
			add_post_meta($post_id, '_cfpfp_text', $_POST['cfptp_text']);
		}
	}
}
add_action('save_post', 'cfptp_save_post');

function cfptp_meta_box() {
	global $post;
	echo '
		<p>
			<label for="cfptp_text" style="display: none;">'.__('Post Title Prefix', 'post-title-prefix').'</label>
			<input type="text" name="cfptp_text" id="cfptp_text" value="'.esc_attr(get_post_meta($post->ID, '_cfpfp_text', true)).'" />
			<input type="hidden" name="cfptp_text_present" id="cfptp_text_present" value="1" />
		</p>
	';
}
function cfptp_add_meta_box() {
	add_meta_box('cfptp_meta_box', __('CF Post Title Prefix', 'post-title-prefix'), 'cfptp_meta_box', 'post', 'side', 'high');
}
add_action('admin_init', 'cfptp_add_meta_box');

//a:23:{s:11:"plugin_name";s:20:"CF Post Title Prefix";s:10:"plugin_uri";s:35:"http://crowdfavorite.com/wordpress/";s:18:"plugin_description";s:113:"Custom field for post title prefix. Automatically added to title in RSS feeds, template tag for showing in theme.";s:14:"plugin_version";s:3:"1.0";s:6:"prefix";s:5:"cfptp";s:12:"localization";s:17:"post-title-prefix";s:14:"settings_title";N;s:13:"settings_link";N;s:4:"init";b:0;s:7:"install";b:0;s:9:"post_edit";s:1:"1";s:12:"comment_edit";b:0;s:6:"jquery";b:0;s:6:"wp_css";b:0;s:5:"wp_js";b:0;s:9:"admin_css";b:0;s:8:"admin_js";b:0;s:8:"meta_box";s:1:"1";s:15:"request_handler";b:0;s:6:"snoopy";b:0;s:11:"setting_cat";b:0;s:14:"setting_author";b:0;s:11:"custom_urls";b:0;}

?>