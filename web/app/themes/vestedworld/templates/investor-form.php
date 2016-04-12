<?php
// Set application_type if it isn't sent along
if (empty($application_type)) {
  $application_type = !empty($position_id) ? 'position' : 'portfolio';
}
?>
<form action="<?= admin_url('admin-ajax.php') ?>" class="signup-form" method="post" enctype="multipart/form-data" novalidate>

  <h2>Become A VestedAngel</h2>

  <fieldset class="contact-details">
    <div><label>First Name (Required)</label><input type="text" name="vestedangel_first_name" placeholder="First Name" required></div>
    <div><label>Last Name (Required)</label><input type="text" name="vestedangel_last_name" placeholder="Last Name" required></div>
    <div><label>Email Address (Required)</label><input type="email" name="vestedangel_email" placeholder="Email Address" required></div>
    <div><label>Phone Number (Required)</label><input type="tel" name="vestedangel_phone" placeholder="Phone Number" required></div>
    <div><label>Company</label><input type="text" name="vestedangel_company" placeholder="Company" required></div>
  </fieldset>

  <fieldset>
    <div class="checkboxes">
      <label><input type="checkbox" name="vestedangel_accredited" value="1"> I am an Accredited Investor</label>
      <label><input type="checkbox" name="vestedangel_accredited" value="0"> I am not an Accredited Investor</label>
    </div>

    <input type="hidden" name="application_type" value="<?= $application_type ?>">
    <input name="action" type="hidden" value="application_submission">

    <?php wp_nonce_field( 'application_form', 'application_form_nonce' ); ?>
  </fieldset>

  <div class="actions">
    <p class="required">* REQUIRED</p>
    <input type="submit" value="Join VestedWorld" class="button">
  </div>
</form>
