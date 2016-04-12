<?php
// Set application_type if it isn't sent along
// if (empty($application_type)) {
//   $application_type = !empty($position_id) ? 'position' : 'portfolio';
// }
?>
<form action="<?= admin_url('admin-ajax.php') ?>" class="application-form" method="post" enctype="multipart/form-data" novalidate>

  <h2>Become A Vestedngel</h2>

  <fieldset>
    <div><label for="vestedangel_" class="sr-only">First Name (Required)</label><input type="text" name="vestedangel_first_name" placeholder="First Name" required></div>
    <div><label for="vestedangel_" class="sr-only">Last Name (Required)</label><input type="text" name="vestedangel_last_name" placeholder="Last Name" required></div>
    <div><label for="vestedangel_" class="sr-only">Email Address (Required)</label><input type="email" name="vestedangel_email" placeholder="Email Address" required></div>
    <div><label for="vestedangel_" class="sr-only">Phone Number (Required)</label><input type="tel" name="vestedangel_phone" placeholder="Phone Number" required></div>
    <div><label for="vestedangel_" class="sr-only">Company</label><input type="text" name="vestedangel_company" placeholder="Company" required></div>

<!--     <input type="hidden" name="application_type" value="<?= $application_type ?>">
    <input name="action" type="hidden" value="application_submission"> -->

    <!-- <?php wp_nonce_field( 'application_form', 'application_form_nonce' ); ?> -->
  </fieldset>

  <input type="submit" value="submit" class="button">
</form>
