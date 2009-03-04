<?php

$q = $_REQUEST['q'];
$root = dirname(dirname(dirname(dirname(__FILE__))));
if (file_exists($root.'/wp-load.php')) {
		// WP 2.6
		require_once($root.'/wp-load.php');
} else {
		// Before 2.6
		require_once($root.'/wp-config.php');
}

echo "<ul>";
   if ($q == 1) {wp_get_archives('type=monthly&show_post_count=1&limit=1');};
   if ($q == 2) {wp_list_categories('orderby=count&order=desc&number=5&show_count=1&title_li=');};
   if ($q == 5) {wp_get_archives('type=monthly&show_post_count=1');};
   if ($q == 6) {wp_list_categories('orderby=count&order=desc&show_count=1&title_li=');};
   if ($q == 3) {wp_get_archives('type=postbypost&limit=10');};
   if ($q == 4) {

$src_count=10; $src_length=30; $pre_HTML='<li><h2>Recent Comments</h2>'; $post_HTML='</li>';


	global $wpdb;

	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,
			SUBSTRING(comment_content,1,$src_length) AS com_excerpt
		FROM $wpdb->comments
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
		WHERE comment_approved = '1' AND comment_type = '' AND post_password = ''
		ORDER BY comment_date_gmt DESC
		LIMIT $src_count";
	$comments = $wpdb->get_results($sql);

//	$output .= "\n<ul>";
	foreach ($comments as $comment) {
		$output .= "\n\t<li><a href=\"" . get_permalink($comment->ID) . "#comment-" . $comment->comment_ID  . "\" title=\"on " . $comment->post_title . "\">" . $comment->comment_author . "</a>: " . strip_tags($comment->com_excerpt) . "...</li>";
	}
//	$output .= "\n</ul>";

	echo $output;

}

     if ($q == 1) { echo "<a href=\"Javascript: showPosts(5);\">More &raquo;</a>"; };
     if ($q == 2) { echo "<a href=\"Javascript: showPosts(6);\">More &raquo;</a>"; };
     
echo "</ul>";


?>
