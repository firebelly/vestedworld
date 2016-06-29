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
// Get child pages and display as separate articles
$args = array(
    'post_type' => 'page',
    'post_parent' => $post->ID,
    'order' => 'ASC'
  );
$child_pages = get_posts($args);
foreach($child_pages as $child_page) {
  include(locate_template("templates/child-page.php"));
}
?>

<div class="active-grid-item-container active-person">
  <div class="grid-nav">
    <div class="previous-item">Previous profile &gt;</div>
    <div class="next-item">&lt; Next profile</div>
  </div>
  <button class="grid-item-deactivate grid-item-toggle plus-button close"><div class="plus"></div></button>
  <div class="bio-content">
    <h1 class="section-title">Profile</h1>
    <div class="item-data-container">

    </div>
  </div>
</div>

<div class="people-sections" id="team">
  <section class="page-section grid-section people management page-nav-section" id="management">
  	<h1 class="section-title page-nav-title">Management</h1>
    <div class="management-container">
    	<?= \Firebelly\PostTypes\Person\get_people(['member_type' => 'management']); ?>
    </div>
  </section>
  <section class="page-section grid-section people board page-nav-section" id="advisory-board">
  	<h1 class="section-title page-nav-title">Advisory Board</h1>
    <div class="grid-intro">
      <p>VestedWorld’s approach is influenced by a diverse team of leaders who uphold our values and support our mission to invest for the greatest global impact.</p>
    </div>
  	<?= \Firebelly\PostTypes\Person\get_people(['member_type' => 'board']); ?>
  </section>
</div>