<?php
// Set application_type if it isn't sent along
if (empty($application_type)) {
  $application_type = 'General Sign Up';
}
if (empty($application_prompt)) {
  $application_prompt = 'Become a VestedAngel';
}

?>

<?php gravity_form( 1, true, false, false, '', true); ?>