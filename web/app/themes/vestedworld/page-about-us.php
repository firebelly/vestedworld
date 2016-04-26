<?php

$title = $post->post_title;
$name = $post->post_name;
$headline = get_post_meta($post->ID, '_cmb2_headline', true);
$summary = get_post_meta($post->ID, '_cmb2_summary', true);
$body = apply_filters('the_content', $post->post_content);

?>

<article id="<?= $name ?>" class="page-section intro-section page-nav-section">
  <?php if ($thumb = \Firebelly\Media\get_post_thumbnail($post->ID, 'large')): ?>
    <div class="image-wrap parallax-parent" style="background-image:url('<?= $thumb ?>');"></div>
  <?php endif; ?>
  <div class="content">
    <h1 class="section-title page-nav-title"><?= $title ?></h1>
    <div class="summary">
      <h2><?= $headline ?></h2>
      <?= !empty($summary) ? '<p>'.$summary.'</p>' : ''; ?>
    </div>
    <div class="body user-content">
      <?= $body ?>
    </div>
  </div>
</article>

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

?>

<div class="people-sections" id="team">
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

  <section class="page-section people management page-nav-section" id="management">
  	<h1 class="section-title page-nav-title">Management</h1>
    <div class="management-container">
    	<?= \Firebelly\PostTypes\Person\get_people(['member_type' => 'management']); ?>
    </div>
  </section>
  <section class="page-section people board page-nav-section" id="advisory-board">
  	<h1 class="section-title page-nav-title">Advisory Board</h1>
    <div class="people-grid-intro">    
      <p>VestedWorld’s approach is influenced by a diverse team of leaders who uphold our values and support our mission to invest for the greatest global impact.</p>
    </div>
  	<?= \Firebelly\PostTypes\Person\get_people(['member_type' => 'board']); ?>
  </section>
</div>