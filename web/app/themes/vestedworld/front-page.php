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

  <!-- what-we-do -->
  <section class="what-we-do">
    <h2 class="tab">What We Do</h2>
    <article>
      <svg role="image" class="icon icon-target"><use xlink:href="#icon-target"></use></svg>
      <h1>Target</h1>
      <p><?= $target ?></p>
      <a href="#" class="learn-more">Learn More</a>
    </article>
    <article>
      <svg role="image" class="icon icon-connect"><use xlink:href="#icon-connect"></use></svg>
      <h1>Connect</h1>
      <p><?= $connect ?></p>
      <a href="#" class="learn-more">Learn More</a>
    </article>
    <article>
      <svg role="image" class="icon icon-invest"><use xlink:href="#icon-invest"></use></svg>
      <h1>Invest</h1>
      <p><?= $invest ?></p>
      <a href="#" class="learn-more">Learn More</a>
    </article>
  </section>

  <div class="home-features">

    <!-- join -->
    <section class="join">
      <h2 class="tab -white -bluetxt">Join</h2>
      <h1>Join VestedWorld</h1>
      <p>We’re always looking for sharp, savvy and socially-conscious investors and advisors to join our growing community. Sign up to learn more.</p>
      <?php include ('templates/join.php'); ?>
    </section>

    <!-- featured -->
    <section class="featured">
      <h2 class="tab -white">Resources</h2>
      <div class="slider">
        <?= \Firebelly\Featured\get_featured(); ?>
      </div>
    </section>

  </div>
</div>
