<?php

function save_read_time_meta($post_id)
{
  $post_content = get_post($post_id)->post_content;
  $read_time = calculate_read_time($post_content);
  update_post_meta($post_id, 'read_time', $read_time);
}
add_action('save_post', 'save_read_time_meta');

function calculate_read_time($content)
{
  $word_count = str_word_count(strip_tags($content));
  $read_time = ceil($word_count / 100);
  return $read_time;
}
