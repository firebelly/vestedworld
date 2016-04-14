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
<div class="people-sections">
  <div class="active-person-container">
    <div class="post-nav">
      <div class="previous-person">Previous profile &gt;</div>
      <div class="next-person">&lt; Next profile</div>
    </div>
    <button class="person-deactivate person-toggle plus-button close"><div class="plus"></div></button>
    <div class="bio-content">
      <h1 class="section-title">Profile</h1>
      <div class="person-data-container">

      </div>
    </div>
  </div>

  <section class="about-section people management" id="management">
  	<h1 class="section-title">Management</h1>
    <div class="management-container">
    	<?= \Firebelly\PostTypes\Person\get_people(['member_type' => 'management']); ?>
    </div>
  </section>
  <section class="about-section people board" id="advisory-board">
  	<h1 class="section-title">Advisory Board</h1>
    <p>VestedWorld’s approach is influenced by a diverse team of leaders who uphold our values and support our mission to invest for the greatest global impact.</p>
  	<?= \Firebelly\PostTypes\Person\get_people(['member_type' => 'board']); ?>
  </section>
</div>