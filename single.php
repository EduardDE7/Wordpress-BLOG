<?php
get_header();
get_sidebar();
?>

<main id="main" class="main">
  <div class="container">

    <div class="post__wrapper">
      <div class="post__body">
        <?php while (have_posts()) : the_post(); ?>

          <?php get_template_part('template-parts/content', get_post_type(), ['is_full' => true]); ?>

          <div class="post__content box">
            <?php the_content(); ?>
          </div>

          <?php if (comments_open() || get_comments_number()) : ?>
            <?php comments_template(); ?>
          <?php endif; ?>

        <?php endwhile; ?>
      </div>
      <div class="post__sidebar">
        <button class="post__sidebar-trigger box">
          <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.2 4.41683L19.2 4.41702L19.2 13.25C19.2 14.209 18.819 15.1288 18.1409 15.8069C17.4628 16.485 16.543 16.866 15.584 16.866L4.416 16.866C3.45698 16.866 2.53723 16.485 1.8591 15.8069C1.18097 15.1288 0.8 14.209 0.8 13.25L0.8 4.41801L0.8 4.41765C0.799787 3.94265 0.893157 3.47227 1.07478 3.03337C1.25641 2.59446 1.52273 2.19565 1.85852 1.8597C2.19432 1.52374 2.59302 1.25725 3.03184 1.07542C3.47066 0.893599 3.94101 0.800013 4.416 0.800013L15.584 0.800014C16.0589 0.800014 16.5292 0.893573 16.968 1.07535C17.4067 1.25712 17.8054 1.52354 18.1412 1.85941C18.477 2.19527 18.7433 2.59399 18.925 3.0328C19.1067 3.47161 19.2001 3.9419 19.2 4.41683ZM13.156 1.76701L13.156 0.967016L12.356 0.967016L4.416 0.967015C3.501 0.967015 2.62349 1.33049 1.97648 1.9775C1.32948 2.6245 0.966003 3.50202 0.966003 4.41701L0.966002 13.25C0.966002 14.165 1.32948 15.0425 1.97648 15.6895C2.62348 16.3365 3.501 16.7 4.416 16.7L12.356 16.7L13.156 16.7L13.156 15.9L13.156 15.899L13.156 1.76701Z" stroke="currentColor" stroke-width="1.6" />
          </svg>
        </button>
        <div class="post__nav">
          <?php
          if (is_single()):
            $prev_post = get_previous_post();
            $next_post = get_next_post();
          ?>
            <div class="post__nav">
              <?php if (!empty($next_post)): ?>
                <a href="<?php echo get_permalink($next_post->ID); ?>" class="post__nav-link">
                  <span class="post__nav-title box"><span><?php echo get_the_title($next_post->ID); ?></span></span>
                  <span class="post__nav-icon box">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M5 12H19M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </span>
                </a>
              <?php endif; ?>
              <?php if (!empty($prev_post)): ?>
                <a href="<?php echo get_permalink($prev_post->ID); ?>"
                  class="post__nav-link">
                  <span class="post__nav-icon box">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </span>
                  <span class="post__nav-title box"><span><?php echo get_the_title($prev_post->ID); ?></span></span>
                </a>

              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>

        <ul class="post__headings box">
          <?php
          $content = get_the_content();
          $headings = get_post_h2_headings($content);

          if (!empty($headings)) {
            foreach ($headings as $heading) {
              echo '<li><a href="#' . $heading['id'] . '">' . $heading['title'] . '</a></li>';
            }
          }
          ?>
        </ul>

        <div class="post__tags box">
          <?php the_tags('', '', ''); ?>
        </div>
        <?php the_reaction_buttons(); ?>
        <?php get_template_part('template-parts/recommended-posts'); ?>
      </div>
    </div>



  </div>
</main>

<?php
get_footer();
