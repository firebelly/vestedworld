<header class="page-header">
	<h1>FAQs</h1>
</header>

<ul class="faq-categories">
<?php
$faq_cats = get_terms('faq_cat');
foreach($faq_cats as $cat) {
	echo "<li><section data-category=\"{$cat->slug}\">";
	echo '<h3 class="tab">' . $cat->name . '</h3>';
	echo do_shortcode("[faqs category=\"{$cat->slug}\"]");
	echo "</section></li>";
}
?>
</ul>