<?php
/*
Template Name: Sign Up
*/

$featured_image = \Firebelly\Media\get_post_thumbnail($post->ID, 'large');
// $form_type = get_post_meta($post->ID, '_cmb2_form_type', true);
?>

<div class="row">
  <div class="page-content">
    <h3 class="tab">About Us</h3>
    <?= apply_filters('the_content', $post->post_content); ?>
  </div>
  <div class="form-content">
    <?php include(locate_template('templates/investor-form.php')); ?>
  </div>
</div>