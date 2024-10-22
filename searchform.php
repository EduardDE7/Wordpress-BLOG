<form role="search" method="get" action="<?php echo home_url('/'); ?>" class="topbar__search-wrapper">
  <input class="topbar__search" type="search" name="s" placeholder="Search" />
  <input type="hidden" name="post_type" value="post">

  <?php if (is_category()): ?>
    <input type="hidden" name="cat" value="<?php echo esc_attr(get_queried_object()->term_id); ?>">
  <?php endif; ?>

  <?php if (is_page('favorites')): ?>
    <input type="hidden" name="favorites" value="1">
  <?php endif; ?>
  <button type="submit">
    <svg
      width="20"
      height="20"
      viewBox="0 0 20 20"
      fill="none"
      xmlns="http://www.w3.org/2000/svg">
      <path
        fill-rule="evenodd"
        clip-rule="evenodd"
        d="M8.94737 0C4.00587 0 0 4.00587 0 8.94737C0 13.8889 4.00587 17.8947 8.94737 17.8947C11.0365 17.8947 12.9583 17.1788 14.481 15.9788L14.9054 16.3936L18.2032 19.6914L18.3023 19.779C18.7153 20.1001 19.3124 20.0709 19.6918 19.6914C20.1029 19.2804 20.1029 18.6139 19.6918 18.2028L16.3854 14.8964L15.9708 14.4912C17.1756 12.9669 17.8947 11.0411 17.8947 8.94737C17.8947 4.00587 13.8889 0 8.94737 0ZM8.94737 2.10526C12.7262 2.10526 15.7895 5.16858 15.7895 8.94737C15.7895 12.7262 12.7262 15.7895 8.94737 15.7895C5.16858 15.7895 2.10526 12.7262 2.10526 8.94737C2.10526 5.16858 5.16858 2.10526 8.94737 2.10526Z"
        fill="currentColor" />
    </svg>
  </button>
</form>