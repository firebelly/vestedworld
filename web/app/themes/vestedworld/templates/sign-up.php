<?php
/*
Template Name: Sign Up
*/

$featured_image = \Firebelly\Media\get_post_thumbnail($post->ID, 'large');
$application_prompt = get_post_meta($post->ID, '_cmb2_application_prompt', true);
$application_type = get_post_meta($post->ID, '_cmb2_application_type', true);
$hide_investor_fields = get_post_meta($post->ID, '_cmb2_application_investor_checkboxes', true);
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