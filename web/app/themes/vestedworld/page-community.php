<?php

$title = $post->post_title;
$name = $post->post_name;
$headline = get_post_meta($post->ID, '_cmb2_headline', true);
$summary = get_post_meta($post->ID, '_cmb2_summary', true);
$body = apply_filters('the_content', $post->post_content);

?>

<article id="<?= $name ?>" class="page-section intro-section page-nav-section">
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

  <section class="page-section people vestedangels page-nav-section" id="vestedangels">
    <h1 class="section-title page-nav-title">VestedAngels</h1>
    <div class="people-grid-intro">    
      <p>VestedAngels are qualified investors who use our online platform to find and invest in the most promising early-stage companies in emerging markets.</p>
      <a href="/sign-up" class="btn -blue section-link">Become a VestedAngel</a>
    </div>
    <?= \Firebelly\PostTypes\Person\get_people(['member_type' => 'vested_angel']); ?>
  </section>
  <section class="page-section people vestedadvisors page-nav-section" id="advisory-vestedadvisors">
    <h1 class="section-title page-nav-title">VestedAdvisors</h1>
    <div class="people-grid-intro">    
      <p>VestedAdvisors are experienced professionals and enterprises who provide strategic, operational and financial advice to help our portfolio companies succeed.</p>
      <a href="/sign-up" class="btn -blue section-link">Become a VestedAdvisor</a>
    </div>
    <?= \Firebelly\PostTypes\Person\get_people(['member_type' => 'vested_advisor']); ?>
  </section>
</div>