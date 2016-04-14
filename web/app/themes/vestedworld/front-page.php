<?php /* Template Name: Front Page */

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

<div class="row">

  <!-- quick and dirty temporary -->
  <section class="join -mobile">
    <h3 class="tab -white -bluetxt">Join</h3>
    <h2>Join VestedWorld</h2>
    <p>We’re always looking for sharp, savvy and socially-conscious investors and advisors to join our growing community. Sign up to learn more.</p>
    <a href="sign-up" class="sign-up-button">Sign up</a>
  </section>

  <!-- what-we-do -->
  <section class="what-we-do">
    <h3 class="tab">What We Do</h3>
    <article>
      <img src="<?= Roots\Sage\Assets\asset_path('images/Target.gif?'.time().'') ?>" alt="Target" class="gif-to-play">
      <h2><a href="/target/">Target</a></h2>
      <p><?= $target ?></p>
      <!-- <a href="/about-us/#" class="learn-more">Learn More</a> -->
    </article>
    <article>
      <img src="<?= Roots\Sage\Assets\asset_path('images/Connect.gif?'.time().'') ?>" alt="Connect" class="gif-to-play">
      <h2><a href="/connect/">Connect</a></h2>
      <p><?= $connect ?></p>
    </article>
    <article>
      <img src="<?= Roots\Sage\Assets\asset_path('images/Invest.gif?'.time().'') ?>" alt="Invest" class="gif-to-play">
      <h2><a href="/invest/">Invest</a></h2>
      <p><?= $invest ?></p>
    </article>
  </section>

  <div class="home-features">

    <!-- join -->
    <section class="join">
      <h3 class="tab -white -bluetxt">Join</h3>
      <h2>Join VestedWorld</h2>
      <p>We’re always looking for sharp, savvy and socially-conscious investors and advisors to join our growing community. Sign up to learn more.</p>
      <a href="sign-up" class="sign-up-button">Sign up</a>
    </section>

    <!-- featured -->
    <section class="featured">
      <h3 class="tab -white">Our Team</h3>
      <div class="slider">
        <?= \Firebelly\Featured\get_featured(); ?>
      </div>
    </section>

  </div>
</div>
