<?php
	//use

//get child pages of About Us.  These are articles.
$args = array(
		'post_type' => 'page',
		'post_parent' => $post->ID,
	);
$pages = get_posts($args);
// get_page_children( $post->post_ID, $pages );
foreach($pages as $page) {
	include(locate_template("templates/about-article.php"));
}

//Get Management and board
?>
<section class="management">
	<h2>Management</h2>
	<?= \Firebelly\PostTypes\Person\get_people(['member_type' => 'management']); ?>
</section>
<section class="board">
	<h2>Advisory Board</h2>
	<?= \Firebelly\PostTypes\Person\get_people(['member_type' => 'board']); ?>
</section>
