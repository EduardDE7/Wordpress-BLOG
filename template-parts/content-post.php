<?php $is_full = $args['is_full'] ?? false; ?>

<article class="post <?php echo $is_full ? 'post--full' : ''; ?>">
  <div class="post__image-wrapper">
    <?php
    if (has_post_thumbnail()) {
      the_post_thumbnail();
    } else {
      echo '<img src="' . get_bloginfo('template_directory') . '/images/placeholder.png" alt="Placeholder" />';
    }
    ?>
  </div>
  <div class="post-info">
    <header class="post-info__top">
      <div class="post-info__meta">
        <span class="post-info__category"><?php the_category(); ?></span>
        <span class="post-info__separator"></span>
        <span class="post-info__read-time">
          <?php
          $read_time = get_post_meta(get_the_ID(), 'read_time', true);
          echo $read_time ? $read_time . ' mins read' : '5 mins read';
          ?>
        </span>
      </div>
      <?php the_favorite_button(); ?>
    </header>
    <h2 class="post-info__title">
      <a href="<?php echo get_the_permalink(); ?>">
        <?php the_title(); ?>
      </a>
    </h2>
    <div class="post-info__footer">
      <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="post-info__author">
        <div class="post-info__author-image-wrapper">
          <?php echo get_avatar(get_the_author_meta('user_email'), 34); ?>
        </div>
        <span class="post-info__author-name"><?php the_author(); ?></span>
      </a>
      <time class="post-info__date" datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php the_time('j F'); ?></time>
    </div>
  </div>
</article>