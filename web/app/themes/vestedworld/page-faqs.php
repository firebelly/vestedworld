<?php
$faq_cats = get_terms('faq_cat');
?>

<header class="page-header">
	<h1>FAQs</h1>
  <div class="resource-filter">
    
    <label for="category">Category</label>
    <div class="select-wrapper">    
      <select id="category" name="category" class="filter-select">
        <option value="all" selected>All</option>
        <?php
        foreach($faq_cats as $cat) {
          echo "<option value=\"{$cat->slug}\">{$cat->name}</option>";
        }
        ?>
      </select>
    </div>
  </div>
</header>

<ul class="faq-sections resource-categories" id="all">
<?php
foreach($faq_cats as $cat) {
	echo "<li><section id=\"{$cat->slug}\" data-category=\"{$cat->slug}\" class=\"sticky-section\">";
	echo '<h3 class="tab sticky-title">' . $cat->name . '</h3>';
	echo do_shortcode("[faqs category=\"{$cat->slug}\"]");
	echo "</section></li>";
}
?>
</ul>
<h2 class="more-questions">Still have questions? <a target="_blank" href="mailto:<?php echo get_option( 'contact_email' ); ?>">Email us</a>.</h2>