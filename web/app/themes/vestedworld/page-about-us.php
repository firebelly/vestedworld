<?php

//get child pages of About Us.  These are articles.
$args = array(
		'post_type' => 'page',
		'post_parent' => $post->ID,
    'order' => 'ASC'
	);
$pages = get_posts($args);
// get_page_children( $post->post_ID, $pages );
foreach($pages as $page) {
	include(locate_template("templates/about-article.php"));
}

//Get Management and board
?>
<section class="about-secton management">
	<h1>Management</h1>
	<?= \Firebelly\PostTypes\Person\get_people(['member_type' => 'management']); ?>
</section>
<section class="about-secton board">
	<h1>Advisory Board</h1>
	<?= \Firebelly\PostTypes\Person\get_people(['member_type' => 'board']); ?>
</section>