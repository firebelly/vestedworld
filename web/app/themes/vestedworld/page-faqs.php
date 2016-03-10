<h2>FAQs</h2>

<ul>
	<?php
	$faq_cats = get_terms('faq_cat');
	foreach($faq_cats as $cat) {
		echo "<li><section class=\"{$cat->slug}\">";
		echo "<h1>{$cat->name}</h1>";
		echo do_shortcode("[faqs category=\"{$cat->name}\"]");
		echo "</section></li>";
	}
	?>
</ul>