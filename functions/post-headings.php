<?php
function add_ids_to_h2_headings($content)
{
  $pattern = '/<h2(.*?)>(.*?)<\/h2>/i';
  $replacement = function ($matches) {
    $id = sanitize_title($matches[2]);
    return "<h2$matches[1] id=\"$id\">$matches[2]</h2>";
  };
  return preg_replace_callback($pattern, $replacement, $content);
}

add_filter('the_content', 'add_ids_to_h2_headings');

function get_post_h2_headings($content)
{
  preg_match_all('/<h2.*?>(.*?)<\/h2>/i', $content, $matches);
  $headings = array();
  foreach ($matches[1] as $heading) {
    $id = sanitize_title($heading);
    $headings[] = array(
      'title' => $heading,
      'id' => $id
    );
  }
  return $headings;
}
