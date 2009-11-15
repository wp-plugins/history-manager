<?php
/*
 * Plugin Name: History Manager
 * Version: 2.5.1
 * Plugin URI: http://andrewanimation.biz/plugins/history-manager/
 * Description: History manager is a widget that shows archives, categories, tags, recent posts, and recent comments in a collapsable mode on the sidebar.
 * Author: Andrew Stephens
 * Author URI: http://andrewanimation.biz/
 */

class HistoryManager extends WP_Widget
{
function HistoryManager(){
    $widget_ops = array('classname' => 'widget_history_manager', 'description' => __( "History manager is a widget that shows archives, categories, recent posts, and recent comments in a collapsable mode on the sidebar.") );
    $this->WP_Widget('history_manager', __('History Manager'), $widget_ops);
}

function widget($args, $instance){

echo "\n\n\n";

extract($args);
//print_r($args);
$title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
$disp0 = empty($instance['disp0']) ? '0' : $instance['disp0'];
$disp1 = empty($instance['disp1']) ? '0' : $instance['disp1'];
$disp2 = empty($instance['disp2']) ? '0' : $instance['disp2'];
$disp3 = empty($instance['disp3']) ? '0' : $instance['disp3'];
$disp4 = empty($instance['disp4']) ? '0' : $instance['disp4'];
$limit = empty($instance['limit']) ? 10 : intval($instance['limit']);
$use_js = empty($instance['use_js']) ? '0' : $instance['use_js'];

echo $before_widget;
if ($title) {
echo $before_title . $title . $after_title."\n";
}

if ($use_js == 1) {
echo "Click to unfold.<br />\n";
}

echo "<ul";
if ($use_js == 1) {
echo " class=\"use_js\"";
}
echo ">";

if ($disp0 == 1) {
   echo "<li><a class=\"whm-link-header\" href=\"#\">Recent Posts</a>";
   echo "<ul>";
   wp_get_archives('type=postbypost&limit='.$limit);
   echo "</ul></li>\n";
}

if ($disp1 == 1) { ?>
<li><a class="whm-link-header" href="#">Recent Comments</a><ul>
<?php $src_length=30; global $wpdb;

	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,
			SUBSTRING(comment_content,1,$src_length) AS com_excerpt
		FROM $wpdb->comments
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
		WHERE comment_approved = '1' AND comment_type = '' AND post_password = ''
		ORDER BY comment_date_gmt DESC
		LIMIT $limit";
	$comments = $wpdb->get_results($sql);

	foreach ($comments as $comment) {
		$output .= "\n\t<li><a href=\"" . get_permalink($comment->ID) . "#comment-" . $comment->comment_ID  . "\" title=\"on " . $comment->post_title . "\">" . $comment->comment_author . "</a>: " . strip_tags($comment->com_excerpt) . "...</li>";
	}

	echo $output;
 ?></ul></li>

<?php };

if ($disp2 == 1) {
   echo "<li><a class=\"whm-link-header\" href=\"#\">Categories</a>";
   echo "<ul>";
   wp_list_categories('orderby=count&order=desc&number='.$limit.'&show_count=1&title_li=');
   echo "</ul></li>\n";
}

if ($disp4 == 1) {
   echo "<li><a class=\"whm-link-header\" href=\"#\">Tags</a>";
   echo "<ul>";
	$tags = get_tags( array('orderby' => 'count', 'order' => 'DESC') );
	foreach ( $tags as $tag ) {
		echo '<li><a href="' . get_tag_link ($tag->term_id) . '" rel="tag">' . $tag->name . '</a> (' . $tag->count . ')</li>';
	}
	echo "</ul></li>\n";
}

if ($disp3 == 1) {
   echo "<li><a class=\"whm-link-header\" href=\"#\">Archives by Month</a>";
   echo "<ul>";
   wp_get_archives('type=monthly&show_post_count=1&limit='.$limit);
   echo "</ul></li>\n";
}

echo "</ul>";
?>

<?php
echo $after_widget;
}

//Update Widget
function update($new_instance, $old_instance){

$instance = $old_instance;
$instance['title'] = strip_tags(stripslashes($new_instance['title']));
$instance['disp0'] = empty($new_instance['disp0']) ? '0' : '1';
$instance['disp1'] = empty($new_instance['disp1']) ? '0' : '1';
$instance['disp2'] = empty($new_instance['disp2']) ? '0' : '1';
$instance['disp3'] = empty($new_instance['disp3']) ? '0' : '1';
$instance['disp4'] = empty($new_instance['disp4']) ? '0' : '1';
$instance['limit'] = empty($new_instance['limit']) ? 10 : intval($new_instance['limit']);
$instance['use_js'] = $new_instance['use_js'];
return $instance;

}

//Widget Edit Form
function form($instance){

//Defaults
$instance = wp_parse_args( (array) $instance, array('title'=>'Post History', 'disp0'=>'1', 'disp1'=>'1', 'disp2'=>'1', 'disp3'=>'1', 'disp4'=>'1', 'use_js'=>'1', 'limit'=>10) );

$title = htmlspecialchars($instance['title']);
$limit = htmlspecialchars($instance['limit']);
$disp0[$instance['disp0']] = " selected=\"selected\"";
$disp1[$instance['disp1']] = " selected=\"selected\"";
$disp2[$instance['disp2']] = " selected=\"selected\"";
$disp3[$instance['disp3']] = " selected=\"selected\"";
$disp4[$instance['disp4']] = " selected=\"selected\"";
$use_js[$instance['use_js']] = " checked=\"checked\"";


echo '<p><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width: 250px;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>'."\n";

echo '<p><label for="' . $this->get_field_name('disp0') . '">Recent Posts</label><br />
<select name="' . $this->get_field_name('disp0') . '">
<option value="1"'.$disp0[1].'>Display</option><option value="0"'.$disp0[0].'>Do Not Display</option>
</select></p>';

echo '<p><label for="' . $this->get_field_name('disp1') . '">Recent Comments</label><br />
<select name="' . $this->get_field_name('disp1') . '">
<option value="1"'.$disp1[1].'>Display</option><option value="0"'.$disp1[0].'>Do Not Display</option>
</select></p>';

echo '<p><label for="' . $this->get_field_name('disp2') . '">Categories</label><br />
<select name="' . $this->get_field_name('disp2') . '">
<option value="1"'.$disp2[1].'>Display</option><option value="0"'.$disp2[0].'>Do Not Display</option>
</select></p>';

echo '<p><label for="' . $this->get_field_name('disp4') . '">Tags</label><br />
<select name="' . $this->get_field_name('disp4') . '">
<option value="1"'.$disp4[1].'>Display</option><option value="0"'.$disp4[0].'>Do Not Display</option>
</select></p>';

echo '<p><label for="' . $this->get_field_name('disp3') . '">Archives by Month</label><br />
<select name="' . $this->get_field_name('disp3') . '">
<option value="1"'.$disp3[1].'>Display</option><option value="0"'.$disp3[0].'>Do Not Display</option>
</select></p>';

echo '<p><label for="' . $this->get_field_name('use_js') . '">Use Accordion Display?</label><br />
<input type="radio" name="' . $this->get_field_name('use_js') . '" value="1"'.$use_js[1].' />Yes
<input type="radio" name="' . $this->get_field_name('use_js') . '" value="0"'.$use_js[0].' />No
</p>';

echo '<p><label for="' . $this->get_field_name('limit') . '">' . __('Display ') . ' <input style="width: 25px;" id="' . $this->get_field_id('limit') . '" name="' . $this->get_field_name('limit') . '" type="text" value="' . $limit . '" /> Posts Each</label></p>'."\n";

}

}// END class

function HM_Init() {
register_widget('HistoryManager');
}
add_action('widgets_init', 'HM_Init');


function history_manager_includes() {
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui');
wp_enqueue_script('jquery-ui-accordion', '/wp-content/plugins/history-manager/ui-accordion.js');
wp_enqueue_script('history-manager', '/wp-content/plugins/history-manager/history-manager.js');
?>
<style type="text/css">
.whm-link-header { font-size: 1.25em; }
</style>
<?php
}

if (!is_admin()) {
add_action('wp_print_scripts', 'history_manager_includes');
}

?>
