<?php
$glossary_posts = get_posts([
  'numberposts' => -1,
  'post_type' => 'glossary',
  'orderby' => 'title',
]);
if (!$glossary_posts):
?>

  <header class="page-header">
    <h1>Glossary</h1>
  </header>
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
      <li class="accordion-item">
        <h2><a href="#{$letter}-{$post->post_name}" class="accordion-trigger">{$term} <svg class="icon icon-arrow-right" role="img"><use xlink:href="#icon-arrow-right"></use></svg><svg class="icon icon-close" role="img"><use xlink:href="#icon-close"></use></svg></a></h2>
        <div id="{$letter}-{$post->post_name}" class="item-content glossary-definition accordion-content user-content">{$definition}</div>
      </li>
HTML;
  endforeach;
?>

<header class="page-header">
  <h1>Glossary</h1>
    <?php

    if ($glossary_posts) { ?>

    <div class="resource-filter">
      
      <label for="category">Letter</label>
      <div class="select-wrapper">    
        <select id="category" name="category" class="filter-select">
          <option value="all" selected>All</option>
          <?php

          foreach($glossary_sections as $letter => $section_html) {
            if (!empty($section_html)) {
              echo "<option value=\"{$letter}\">{$letter}</option>";
            }
          }

          ?>

        </select>
      </div>
    </div>

    <?php } ?>
</header>

  <ul class="glossary-sections resource-categories accordion">
  <?php
  foreach($glossary_sections as $letter => $section_html) {
    if (!empty($section_html)) {
      echo "<li><section id=\"{$letter}\" data-category=\"{$letter}\" class=\"sticky-section\">";
      echo '<h3 class="tab sticky-title">' . $letter . '</h3>';
      echo '<ul class="resource-list">' . $section_html . '</ul>';
      echo "</section></li>";
    }
  }
  ?>
  </ul>

<?php endif; ?>
