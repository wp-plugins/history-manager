<?php
/*
Plugin Name: History Manager
Plugin URI: http://andrewanimation.biz/games/history-manager-plugin/
Description: Allows your visitors to see your old posts by category, by month, by most recent, and shows your most recent comments, all powered with some AJAX so it loads when your visitor wants it.
Author: Andrew Stephens
Version: 1.1
Author URI: http://andrewanimation.biz/
*/


function history_manager_includes() {
wp_enqueue_script('jquery');
wp_enqueue_script('history_manager', '/wp-content/plugins/history-manager/history-manager.js');
}

//Control Panel on Admin Menu
function history_manager_control() {
$data = get_option('history_manager');
  ?>
<p><label>Title<input name="hm_title" type="text" value="<?php echo $data['title']; ?>" /></label></p>
<p><label>Background Color #<input name="hm_color" type="text" value="<?php echo $data['color']; ?>" /></label></p>

  <?php
   if (isset($_POST['hm_color'])){
    $data['color'] = attribute_escape($_POST['hm_color']);
    $data['title'] = attribute_escape($_POST['hm_title']);
    update_option('history_manager', $data);
  }
}


function widget_ajaxhistory_init() {
	if ( !function_exists('register_sidebar_widget') )
		return;

//Plugin Itself
function widget_ajaxhistory($args) {
extract($args);

echo '<script type="text/javascript">var siteurl =  "'.get_bloginfo('url').'";</script>';

//$data = get_option('history-manager');
$data = get_option('history_manager');
echo $before_widget . $before_title . $data['title'] . $after_title;

//echo "<script type=\"text/javascript\" src=\""; bloginfo('url'); echo "/wp-content/plugins/history-manager/history-manager.js\"></script>\n";
echo "<script type=\"text/javascript\">var siteurl = \""; bloginfo('url'); echo "\";</script>\n";
echo "<ul class=\"history-manager\">";
echo "<li><a href=\"Javascript:;\" class=\"hist-man\" onClick=\"Javascript:showPosts(3);\">Recent Posts</a></li>\n";
echo "<li><a href=\"Javascript:;\" class=\"hist-man\" onClick=\"Javascript:showPosts(4);\">Recent Comments</a></li>\n";
echo "<li><a href=\"Javascript:;\" class=\"hist-man\" onClick=\"Javascript:showPosts(1);\">Archives by Month</a></li>\n";
echo "<li><a href=\"Javascript:;\" class=\"hist-man\" onClick=\"Javascript:showPosts(2);\">Categories</a></li>\n";
echo "</ul>";
echo "<div id=\"post-history-return\" style=\"background-color: #".$data['color']."; border-bottom: 1px solid black; overflow: hidden; height: 0px;\">Loading</div>\n";
echo "<div id=\"post-history-load\">Loading...<img src=\""; bloginfo('url'); echo "/wp-content/plugins/history-manager/ajax-loader.gif\" alt=\"...\" /></div>";

echo $after_widget;

}

register_sidebar_widget(array('History Manager', 'widgets'), 'widget_ajaxhistory');
register_widget_control('History Manager',  'history_manager_control');

}

register_activation_hook( __FILE__, 'history_manager_activate' );
function history_manager_activate() {
$data = array( 'color' => 'c0c0c0' ,'title' => 'Post History');
if (!get_option('history_manager')){
   add_option('history_manager' , $data);
} else {
   update_option('history_manager' , $data);
}
}


register_deactivation_hook( __FILE__, 'history_manager_deactivate');
function deactivate(){ delete_option('history_manager'); }



// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_ajaxhistory_init');
add_action('wp_print_scripts', 'history_manager_includes');

?>
