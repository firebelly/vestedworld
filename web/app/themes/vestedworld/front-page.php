<?php
// Populate custom areas
$target  = apply_filters('the_content', get_post_meta( $post->ID, '_cmb2_target', true ));
$connect = apply_filters('the_content', get_post_meta( $post->ID, '_cmb2_connect', true ));
$invest  = apply_filters('the_content', get_post_meta( $post->ID, '_cmb2_invest', true ));
?>

<!-- headline carousel -->
<section class="headlines">
  <div class="slider">
    <?= \Firebelly\PostTypes\Headlines\get_headlines(); ?>
  </div>
</section>

<div class="row clearfix">

  <!-- quick and dirty temporary -->
  <section class="join -mobile">
    <h3 class="tab -white -bluetxt">Join</h3>
    <h2>Join VestedWorld</h2>
    <p>Are you a sharp, savvy and socially conscious investor or advisor? Join our growing community.</p>
    <a href="sign-up" class="sign-up-button">Sign up to learn more</a>
  </section>

  <!-- what-we-do -->
  <section class="what-we-do">
    <h3 class="tab -gray">What We Do</h3>
    <article>
      <img data-src="<?= Roots\Sage\Assets\asset_path('images/Target.gif?'.time().'') ?>" alt="Target" class="gif-to-play">
      <h2>Target</h2>
      <p><?= $target ?></p>
    </article>
    <article>
      <img data-src="<?= Roots\Sage\Assets\asset_path('images/Connect.gif?'.time().'') ?>" alt="Connect" class="gif-to-play">
      <h2>Connect</h2>
      <p><?= $connect ?></p>
    </article>
    <article>
      <img data-src="<?= Roots\Sage\Assets\asset_path('images/Invest.gif?'.time().'') ?>" alt="Invest" class="gif-to-play">
      <h2>Invest</h2>
      <p><?= $invest ?></p>
    </article>
  </section>

  <div class="home-features">

    <!-- join -->
    <section class="join">
      <h3 class="tab -white -bluetxt">Join</h3>
      <h2>Join VestedWorld</h2>
      <p>Are you a sharp, savvy and socially conscious investor or advisor? Join our growing community.</p>
      <a href="sign-up" class="sign-up-button">Sign up to learn more</a>
    </section>

    <!-- featured -->
    <section class="featured">
      <?= \Firebelly\Featured\get_featured(); ?>
    </section>

  </div>
</div>
