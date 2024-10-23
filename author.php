<?php
get_header();
get_sidebar();

$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
?>

<main class="main" id="main">
  <div class="container-grid">
    <header class="header">
      <?php
      get_template_part('template-parts/topbar', args: [
        'title' => 'Posts by ' . $curauth->display_name
      ]);
      get_template_part('template-parts/categories', 'list');
      ?>
    </header>

    <?php
    $author_posts = new WP_Query([
      'author' => $curauth->ID,
      'posts_per_page' => get_option('posts_per_page'),
      'paged' => get_query_var('paged') ? get_query_var('paged') : 1
    ]);

    if ($author_posts->have_posts()) :
      while ($author_posts->have_posts()) : $author_posts->the_post();
        get_template_part('template-parts/content', 'post');
      endwhile;


      wp_reset_postdata();
    else:
    ?>
      <div class="box">
        <p class="main__message">Sorry, this author hasn't published any posts yet.</p>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>