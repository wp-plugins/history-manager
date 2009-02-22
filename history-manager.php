<?php
/**
 * @package AJAX History_Manager
 * @author Andrew Stephens
 * @version 1.0
 */
/*
Plugin Name: History Manager
Plugin URI: http://andrewanimation.biz/games/history-manager-plugin/
Description: Allows your visitors to see your old posts by category, by month, by most recent, and shows your most recent comments, all powered with some AJAX so it loads when your visitor wants it.
Author: Andrew Stephens
Version: 1.0
Author URI: http://andrewanimation.biz/
*/


function widget_ajaxhistory_init() {
	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_ajaxhistory($args) {
		extract($args);

echo '<script type="text/javascript">
  var siteurl =  "'.get_bloginfo('url').'";
  Pic1 = new Image();
  Pic1.src=siteurl+"/wp-content/plugins/history-manager/ajax-loader.gif";

</script>';

//echo "<li><h2>Post History</h2>\n";
        echo $before_widget . $before_title . __("Post History") . $after_title;

        echo "<script type=\"text/javascript\" src=\""; bloginfo('url'); echo "/wp-content/plugins/history-manager/ajax2.js\"></script>\n";
        echo "<script type=\"text/javascript\">var siteurl = \""; bloginfo('url'); echo "\";</script>\n";
        echo "<ul class=\"history-manager\">";
        echo "<li><a href=\"Javascript:;\" onClick=\"Javascript:showPosts(3); return false;\">Recent Posts</a></li>\n";
        echo "<li><a href=\"Javascript:;\" onClick=\"Javascript:showPosts(4)\">Recent Comments</a></li>\n";
        echo "<li><a href=\"Javascript:;\" onClick=\"Javascript:showPosts(1)\">Archives by Month</a></li>\n";
        echo "<li><a href=\"Javascript:;\" onClick=\"Javascript:showPosts(2)\">Categories</a></li>\n";
        echo "</ul>";
        echo "<div id=\"post-history-return\" style=\"background-color: #c0c0c0; border-bottom: 1px solid black; overflow: hidden; height: 0px;\">Loading</div>\n";
//visibility: hidden; border: 1px solid black;

//       echo "</li>\n";
         echo $after_widget;
        }

register_sidebar_widget(array('AJAX History Manager', 'widgets'), 'widget_ajaxhistory');

}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_ajaxhistory_init');

?>
