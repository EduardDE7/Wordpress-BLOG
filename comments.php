<?php
if (post_password_required()) {
  return;
}
?>

<div id="comments" class="post__comments box">

  <?php if (have_comments()) : ?>
    <h2 class="comments-title">
      Comments
    </h2>

    <?php the_comments_navigation(); ?>

    <ol class="comment-list">
      <?php wp_list_comments(
        array(
          'style'      => 'ol',
          'short_ping' => true,
        )
      ); ?>
    </ol>

    <?php
    the_comments_navigation();

    if (! comments_open()) :
    ?>
      <p class="no-comments"><?php esc_html_e('Comments are closed.', 'wpdev'); ?></p>
    <?php
    endif;

  endif;

  if (is_user_logged_in()) {
    comment_form();
  } else {
    ?>
    <div>
      <p>You must be logged in to leave a comment. Please <a href="<?php echo get_permalink(get_page_by_path('login')); ?>" class="main-link">login</a>.</p>
      <p class="text-secondary">
        Don't have an account? It's free, and only takes a minute to
        <a href="<?php echo get_permalink(get_page_by_path('register')); ?>" class="main-link">register</a>!
      </p>
    </div>
  <?php
  }
  ?>

</div>