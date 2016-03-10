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

<!-- our approach -->
<section class="approach">
	<h1>Our Approach</h1>
	<article>
		<h1>Target</h1>
		<svg role="image" class="icon icon-target"><use xlink:href="#icon-target"></use></svg>
		<p><?= $target ?></p>
		<a href="">Learn More</a>
	</article>
	<article>
		<h1>Connect</h1>
		<svg role="image" class="icon icon-connect"><use xlink:href="#icon-connect"></use></svg>
		<p><?= $connect ?></p>
		<a href="">Learn More</a>
	</article>
	<article>
		<h1>Invest</h1>
		<svg role="image" class="icon icon-invest"><use xlink:href="#icon-invest"></use></svg>
		<p><?= $invest ?></p>
		<a href="">Learn More</a>
	</article>
</section>


<!-- join -->
<section class="join">
	<h1>Join VestedWorld</h1>
	<p>We’re always looking for sharp, savvy and socially-conscious investors and advisors to join our growing community. Sign up to learn more.</p>
	<?php include ('templates/join.php'); ?>
</section>

<!-- featured -->
<section class="featured">
	<h1>Resources</h1>
  	<div class="slider">
    	<?= \Firebelly\Featured\get_featured(); ?>
  	</div>
</section>