<header class="page-header">
  <h1>Glossary</h1>
</header>

<?php
$glossary_posts = get_posts([
  'numberposts' => -1,
  'post_type' => 'glossary',
  'orderby' => 'title',
]);
if (!$glossary_posts):
?>

  <div class="alert alert-warning">
    <p>No terms found.</p>
  </div>

<?php
else:

  $glossary_sections = [];
  foreach (range('a','z') as $letter) {
    $glossary_sections[$letter] = '';
  }

  foreach ($glossary_posts as $post):
    $term = $post->post_title;
    $definition = apply_filters('the_content', $post->post_content);
    $letter = strtolower(substr($term,0,1));

    $glossary_sections[$letter] .= <<<HTML
      <li>
        <h2><a href="#{$letter}-{$post->post_name}">{$term} <span class="icon">&gt;</span></a></h2>
        <div id="{$letter}-{$post->post_name}" class="glossary-definition user-content">{$definition}</div>
      </li>
HTML;
  endforeach;
?>

  <ul class="glossary-sections">
  <?php
  foreach($glossary_sections as $letter => $section_html) {
    if (!empty($section_html)) {
      echo "<li><section data-category=\"{$letter}\">";
      echo '<h3 class="tab">' . $letter . '</h3>';
      echo '<ul class="glossary-list">' . $section_html . '</ul>';
      echo "</section></li>";
    }
  }
  ?>
  </ul>

<?php endif; ?>
