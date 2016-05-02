<?php
// Set application_type if it isn't sent along
if (empty($application_type)) {
  $application_type = 'General Sign Up';
}
if (empty($application_prompt)) {
  $application_prompt = 'Become a VestedAngel';
}
?>
<form action="<?= admin_url('admin-ajax.php') ?>" class="application-form" method="post" enctype="multipart/form-data" novalidate>

  <h2><?= $application_prompt ?></h2>

  <fieldset class="contact-details">
    <div><label class="sr-only">First Name (Required)</label><input type="text" name="application_first_name" placeholder="First Name*" required></div>
    <div><label class="sr-only">Last Name (Required)</label><input type="text" name="application_last_name" placeholder="Last Name*" required></div>
    <div><label class="sr-only">Email Address (Required)</label><input type="email" name="application_email" placeholder="Email Address*" required></div>
    <div><label class="sr-only">Phone Number (Required)</label><input type="tel" name="application_phone" placeholder="Phone Number*" required></div>
    <div><label class="sr-only">Company</label><input type="text" name="application_company" placeholder="Company"></div>
  </fieldset>

  <fieldset>

    <?php if (!empty($hide_investor_fields)) { ?>
      <input type="hidden" name="application_accredited" value="n/a">
    <?php } else { ?>
      <div class="checkboxes">
        <label><input type="radio" name="application_accredited" value="yes"> I am an Accredited Investor</label>
        <label><input type="radio" name="application_accredited" value="no"> I am not an Accredited Investor</label>
      </div>
    <?php } ?>

    <input type="hidden" name="application_type" value="<?= $application_type ?>">
    <input name="action" type="hidden" value="application_submission">

    <?php wp_nonce_field( 'application_form', 'application_form_nonce' ); ?>
  </fieldset>

  <div class="actions">
    <p class="required">* REQUIRED</p>
    <div class="btn-wrap btn -blue">
      <input type="submit" value="Join VestedWorld" class="submit">
    </div>
  </div>
</form>
