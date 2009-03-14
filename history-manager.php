<?php
/*
Plugin Name: History Manager
Plugin URI: http://andrewanimation.biz/games/history-manager-plugin/
Description: History manager is a widget that shows archives, categories, recent posts, and recent categories in a collapsable mode on the sidebar.
Author: Andrew Stephens
Version: 2.0
Author URI: http://andrewanimation.biz/
*/

//Adding Scripts
function history_manager_includes() {
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui');
wp_enqueue_script('jquery-ui-accordion', '/wp-content/plugins/history-manager/ui-accordion.js');
//echo '<link rel="stylesheet" type="text/css" href="jquery-ui.css" />';
}

//Control Panel on Admin Menu
function history_manager_control() {
$data = get_option('history_manager');?>

<p><label>Title<input name="hm_title" type="text" value="<?php echo $data['title']; ?>" /></label></p>
<!--<p><label>Background Color #<input name="hm_color" type="text" value="<?php echo $data['color']; ?>" /></label></p>-->

<p>Display:<br />
<label><input type="checkbox" value="1" name="hm_disp0"<?php if ($data['disp'][0] == 1) { echo " checked"; } ?>>Recent Posts</label><br />
<label><input type="checkbox" value="1" name="hm_disp1"<?php if ($data['disp'][1] == 1) { echo " checked"; } ?>>Recent Comments</label><br />
<label><input type="checkbox" value="1" name="hm_disp2"<?php if ($data['disp'][2] == 1) { echo " checked"; } ?>>Categories</label><br />
<label><input type="checkbox" value="1" name="hm_disp3"<?php if ($data['disp'][3] == 1) { echo " checked"; } ?>>Archives by Month</label><br />
</p>

<?php
if (isset($_POST['hm_title'])) {
//    $data['color'] = attribute_escape($_POST['hm_color']);
    $data['title'] = attribute_escape($_POST['hm_title']);
    $data['disp'] = array($_POST['hm_disp0'], $_POST['hm_disp1'], $_POST['hm_disp2'], $_POST['hm_disp3']);
    update_option('history_manager', $data);
  }
}


function history_manager_init() {
	if ( !function_exists('register_sidebar_widget') )
		return;

//Plugin Itself
function widget_historymanager($args) {
extract($args);
$data = get_option('history_manager');
echo $before_widget . $before_title . $data['title'] . $after_title;

$dat = $data['disp'];

?>

<?php if ($dat[0] == 1) { ?>
<ul><li><span>Recent Posts</span>
<ul><?php wp_get_archives('type=postbypost&limit=10'); ?></ul>

<?php } if ($dat[1] == 1) { ?>
</li><li><span>Recent Comments</span><ul>
<?php $src_count=5; $src_length=30; global $wpdb;

	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,
			SUBSTRING(comment_content,1,$src_length) AS com_excerpt
		FROM $wpdb->comments
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
		WHERE comment_approved = '1' AND comment_type = '' AND post_password = ''
		ORDER BY comment_date_gmt DESC
		LIMIT $src_count";
	$comments = $wpdb->get_results($sql);

	foreach ($comments as $comment) {
		$output .= "\n\t<li><a href=\"" . get_permalink($comment->ID) . "#comment-" . $comment->comment_ID  . "\" title=\"on " . $comment->post_title . "\">" . $comment->comment_author . "</a>: " . strip_tags($comment->com_excerpt) . "...</li>";
	}

	echo $output;
 ?></ul>

<?php } if ($data['disp'][2] == 1) { ?>
</li><li><span>Categories</span>
<ul><?php wp_list_categories('orderby=count&order=desc&number=5&show_count=1&title_li='); ?></ul>

<?php } if ($data['disp'][3] == 1) { ?>
</li><li><span>Archives by Month</span>
<ul><?php wp_get_archives('type=monthly&show_post_count=1&limit=1'); ?></ul>
<?php } ?>
</li></ul>



<script type="text/javascript">
var $j = jQuery.noConflict();
$j(document).ready(function() {

$j("#history-manager ul").accordion({
header: 'span',
event: 'click',
activeClass: 'selected',
autoHeight: false,
active: false
});


});
</script>


<?php

echo $after_widget;

}

register_sidebar_widget(array('History Manager', 'widgets'), 'widget_historymanager');
register_widget_control('History Manager',  'history_manager_control');

}

register_activation_hook( __FILE__, 'history_manager_activate' );
function history_manager_activate() {
$data = array( 'title' => 'Post History' , 'disp' => array(1,1,1,1) );
if (!get_option('history_manager')){
   add_option('history_manager' , $data);
} else {
   update_option('history_manager' , $data);
}
}


register_deactivation_hook( __FILE__, 'history_manager_deactivate');
function history_manager_deactivate(){ delete_option('history_manager'); }



// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'history_manager_init');
add_action('wp_print_scripts', 'history_manager_includes');


?>
