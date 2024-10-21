<nav class="sidebar">
  <a href="<?php echo home_url(); ?>" class="sidebar__logo">
    <span class="sidebar__logo-icon">B</span>
    <span class="sidebar__text">Blogger</span>
  </a>
  <ul class="sidebar__nav-menu">
    <li class="sidebar__nav-item">
      <a href="<?php echo home_url(); ?>" class="sidebar__nav-link <?php echo (is_home() || is_front_page() || is_category()) ? 'sidebar__nav-link--active' : ''; ?>" aria-label="Home">
        <svg
          width="20"
          height="20"
          viewBox="0 0 20 20"
          fill="none"
          xmlns="http://www.w3.org/2000/svg">
          <path
            d="M10.0021 4.56562L17.5 11.7917V18.656C17.5 18.8812 17.4112 19.0963 17.2546 19.2543C17.0981 19.4121 16.8867 19.5 16.6672 19.5H12.6664C12.6236 19.5 12.5818 19.4829 12.5502 19.4511C12.5185 19.4192 12.5 19.375 12.5 19.328V13.6158C12.5 13.3954 12.4132 13.1832 12.2574 13.0261C12.1015 12.8689 11.8891 12.7798 11.6668 12.7798H8.33486C8.11252 12.7798 7.90014 12.8689 7.74422 13.0261C7.58843 13.1832 7.50167 13.3954 7.50167 13.6158V19.328C7.50167 19.375 7.48312 19.4192 7.45145 19.4511C7.41991 19.4829 7.37804 19.5 7.33528 19.5H3.33278C3.11328 19.5 2.90191 19.4121 2.7454 19.2543C2.58877 19.0963 2.50002 18.8812 2.5 18.656C2.5 18.656 2.5 18.656 2.5 18.656L2.50124 11.7917L10.0021 4.56562Z"
            stroke=currentColor />
          <path
            d="M10.5628 2.34494C10.4116 2.2003 10.2104 2.11957 10.0012 2.11957C9.79203 2.11957 9.59093 2.20023 9.43974 2.34476C9.43968 2.34482 9.43961 2.34488 9.43955 2.34494L1.33427 10.0901L1.33324 10.0911C1.32102 10.1028 1.30652 10.1119 1.29065 10.1179C1.27478 10.1239 1.25786 10.1266 1.24092 10.1258C1.22398 10.125 1.20737 10.1208 1.19211 10.1134C1.17685 10.106 1.16325 10.0956 1.15215 10.0828L1.15131 10.0818C1.11838 10.044 1.10757 9.96046 1.17322 9.89667C1.17338 9.89651 1.17354 9.89635 1.17371 9.89619L9.48082 1.94839L9.48598 1.94346L9.49099 1.93839C9.5969 1.83111 9.77679 1.75 10 1.75C10.225 1.75 10.4057 1.83152 10.5113 1.93832L10.5112 1.93846L10.5213 1.94811L12.7838 4.11139L13.6293 4.91985V3.75V2.5C13.6293 2.46685 13.6425 2.43505 13.6659 2.41161C13.6894 2.38817 13.7212 2.375 13.7543 2.375H15.6293C15.6625 2.375 15.6943 2.38817 15.7177 2.41161C15.7411 2.43505 15.7543 2.46685 15.7543 2.5V6.7418V6.95542L15.9087 7.10309L18.8305 9.8984L18.8306 9.89849C18.8563 9.923 18.8734 9.95996 18.8749 10.001C18.8765 10.0411 18.8631 10.0696 18.8462 10.0871L18.846 10.0872C18.823 10.1111 18.7916 10.1248 18.7585 10.1254C18.7254 10.1261 18.6934 10.1136 18.6695 10.0907L18.6689 10.0901L10.5628 2.34494ZM10.5628 2.34494L10.2172 2.70625M10.5628 2.34494L10.5626 2.34474L10.2172 2.70625M10.2172 2.70625L18.3235 10.4516M10.2172 2.70625C10.159 2.65062 10.0817 2.61957 10.0012 2.61957C9.92071 2.61957 9.84333 2.65062 9.78517 2.70625L1.6797 10.4516C1.61856 10.5103 1.54611 10.556 1.46675 10.5859C1.38739 10.6157 1.30278 10.6291 1.21808 10.6253C1.13337 10.6214 1.05034 10.6003 0.974038 10.5633C0.897734 10.5264 0.829753 10.4742 0.774234 10.4102C0.55314 10.1562 0.583609 9.7707 0.826577 9.53633L9.13517 1.58711C9.34689 1.37266 9.66095 1.25 10 1.25C10.3402 1.25 10.6551 1.37266 10.8668 1.58672L18.3235 10.4516M18.3235 10.4516C18.4429 10.5661 18.6028 10.6286 18.7682 10.6253C18.9336 10.6221 19.091 10.5534 19.2059 10.4344C19.4457 10.1859 19.4258 9.77578 19.1762 9.53711L18.3235 10.4516Z"
            stroke=currentColor />
        </svg>

        <span class="sidebar__text">Home</span>
      </a>
    </li>
    <li class="sidebar__nav-item">
      <a href="#" class="sidebar__nav-link" aria-label="Navigation item 2">
        <svg
          width="18"
          height="20"
          viewBox="0 0 18 20"
          fill="none"
          xmlns="http://www.w3.org/2000/svg">
          <path
            d="M16.0214 10.3484L16.0214 10.3484L17.1292 12.0712C17.9684 13.3777 17.3129 15.1289 15.894 15.5298L15.8937 15.5299C11.3865 16.8063 6.61339 16.8063 2.10618 15.5299L2.10589 15.5298C0.686956 15.1289 0.0314914 13.3777 0.870634 12.0712L1.97849 10.3484L1.97919 10.3473C2.48383 9.55812 2.75165 8.64077 2.75094 7.704C2.75094 7.70396 2.75094 7.70391 2.75094 7.70387C2.75094 7.70377 2.75094 7.70367 2.75094 7.70358L2.75094 7C2.75094 3.39279 5.56612 0.5 8.99994 0.5C12.4339 0.5 15.2499 3.39289 15.2499 7V7.704C15.2499 8.64329 15.5167 9.56342 16.0214 10.3484ZM9.00171 19.5L8.99817 19.5C8.37241 19.5022 7.75433 19.3622 7.19062 19.0905C7.07263 19.0337 6.95767 18.9714 6.84608 18.9039C8.2798 19.0157 9.72008 19.0157 11.1538 18.9039C11.0422 18.9714 10.9272 19.0337 10.8093 19.0905C10.2455 19.3622 9.62747 19.5022 9.00171 19.5Z"
            stroke=currentColor />
        </svg>

        <span class="sidebar__text">Notifications</span>
      </a>
    </li>
    <li class="sidebar__nav-item">
      <a href="<?php echo get_permalink(get_page_by_path('favorites')); ?>" class="sidebar__nav-link <?php echo is_page('favorites') ? 'sidebar__nav-link--active' : ''; ?>" aria-label="Favorites">
        <svg
          width="16"
          height="20"
          viewBox="0 0 16 20"
          fill="none"
          xmlns="http://www.w3.org/2000/svg">
          <path
            d="M7.02731 15.9604L6.74998 15.7756L6.47265 15.9604L1.19414 19.479C1.19406 19.4791 1.19397 19.4791 1.19388 19.4792C1.17512 19.4916 1.15335 19.4987 1.13086 19.4998C1.10827 19.5009 1.08582 19.4958 1.06588 19.4852C1.04595 19.4745 1.02928 19.4586 1.01766 19.4392C1.00603 19.4198 0.99989 19.3976 0.999878 19.375L0.999878 5.00004C0.999878 4.4696 1.2106 3.96088 1.58568 3.5858C1.96076 3.21072 2.46947 3 2.99992 3H10.5C11.0305 3 11.5392 3.21072 11.9143 3.5858C12.2894 3.96088 12.5001 4.4696 12.5001 5.00004V19.375C12.5001 19.3976 12.4939 19.4198 12.4823 19.4392C12.4707 19.4586 12.454 19.4745 12.4341 19.4852C12.4141 19.4958 12.3917 19.5009 12.3691 19.4998C12.3466 19.4987 12.3248 19.4916 12.306 19.4792C12.306 19.4791 12.3059 19.4791 12.3058 19.479L7.02731 15.9604Z"
            stroke=currentColor />
          <path
            d="M14.8058 16.979L14.8053 16.9787L14.75 16.942V2.50004C14.75 2.0359 14.5656 1.59078 14.2374 1.26259C13.9093 0.934397 13.4641 0.75002 13 0.75002H4.53164C4.82783 0.586136 5.16097 0.500011 5.49987 0.5C5.49988 0.5 5.49988 0.5 5.49989 0.5H13C13.5304 0.5 14.0392 0.710718 14.4142 1.0858C14.7893 1.46088 15 1.9696 15 2.50004V16.875C15 16.8976 14.9939 16.9198 14.9823 16.9392C14.9706 16.9586 14.954 16.9745 14.934 16.9852C14.9141 16.9958 14.8916 17.0009 14.8691 16.9998C14.8465 16.9987 14.8246 16.9916 14.8058 16.979Z"
            fill=currentColor
            stroke=currentColor />
        </svg>

        <span class="sidebar__text">Favorites</span>
      </a>
    </li>
    <li class="sidebar__nav-item">
      <a href="#" class="sidebar__nav-link" aria-label="Navigation item 4">
        <svg
          width="18"
          height="20"
          viewBox="0 0 18 20"
          fill="none"
          xmlns="http://www.w3.org/2000/svg">
          <path
            d="M15.6786 19.5H7.07143V14.7857H15.6786C16.0004 14.7857 16.3291 14.9355 16.5855 15.2372C16.8441 15.5413 17 15.969 17 16.4286V17.8571C17 18.3167 16.8441 18.7444 16.5855 19.0485C16.3291 19.3503 16.0004 19.5 15.6786 19.5ZM15.6786 12.3571H7.07143V7.64286H15.6786C16.0004 7.64286 16.3291 7.7926 16.5855 8.09431C16.8441 8.39849 17 8.82611 17 9.28571V10.7143C17 11.1739 16.8441 11.6015 16.5855 11.9057C16.3291 12.2074 16.0004 12.3571 15.6786 12.3571ZM4.85714 7.64286V12.3571H2.32143C1.99962 12.3571 1.6709 12.2074 1.41445 11.9057C1.1559 11.6015 1 11.1739 1 10.7143V9.28571C1 8.82611 1.1559 8.39849 1.41445 8.09431C1.6709 7.79261 1.99962 7.64286 2.32143 7.64286H4.85714ZM15.6786 5.21429H7.07143V0.5H15.6786C16.0004 0.5 16.3291 0.649748 16.5855 0.951453C16.8441 1.25563 17 1.68326 17 2.14286V3.57143C17 4.03103 16.8441 4.45866 16.5855 4.76283C16.3291 5.06454 16.0004 5.21429 15.6786 5.21429ZM4.85714 0.5V5.21429H2.32143C1.99962 5.21429 1.6709 5.06454 1.41445 4.76283C1.1559 4.45866 1 4.03103 1 3.57143V2.14286C1 1.68326 1.1559 1.25563 1.41445 0.951453C1.6709 0.649748 1.99962 0.5 2.32143 0.5H4.85714ZM4.85714 14.7857V19.5H2.32143C1.99962 19.5 1.6709 19.3503 1.41445 19.0485C1.1559 18.7444 1 18.3167 1 17.8571V16.4286C1 15.969 1.1559 15.5413 1.41445 15.2372C1.6709 14.9355 1.99962 14.7857 2.32143 14.7857H4.85714Z"
            stroke=currentColor />
        </svg>

        <span class="sidebar__text">List</span>
      </a>
    </li>
  </ul>
  <a href="<?php echo esc_url(is_user_logged_in() ?
              get_permalink(get_page_by_path('profile')) :
              get_permalink(get_page_by_path('login'))); ?>"
    class="sidebar__profile">
    <?php
    if (is_user_logged_in()) {
      $current_user = wp_get_current_user();
      echo get_avatar($current_user->user_email, 34);
    } else { ?>
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M7.57387 3.00021C7.49997 3.79247 7.49999 4.76369 7.5 5.9504V6V14V14.0496C7.49999 15.2363 7.49998 16.2075 7.57387 16.9998C6.60188 16.9985 5.88398 16.9886 5.32313 16.9133C4.6977 16.8294 4.34329 16.6725 4.08579 16.4147C3.82796 16.1565 3.67109 15.8017 3.58704 15.1764C3.50106 14.5367 3.5 13.6926 3.5 12.5V7.5C3.5 6.30737 3.50106 5.46305 3.58705 4.82322C3.6711 4.19772 3.82799 3.84312 4.08555 3.58555C4.34312 3.32799 4.69772 3.1711 5.32322 3.08705C5.88405 3.01168 6.60198 3.00156 7.57387 3.00021Z" fill="transparent" stroke="currentColor" />
        <path d="M8.23235 1.23275L8.23275 1.23235C8.56331 0.901424 9.01331 0.707836 9.77489 0.605414C10.5508 0.501062 11.5719 0.5 13 0.5H14C15.4281 0.5 16.4492 0.501062 17.2251 0.605414C17.9867 0.707836 18.4367 0.901424 18.7672 1.23235L18.7676 1.23275C19.0986 1.56331 19.2922 2.01331 19.3946 2.77489C19.4989 3.55082 19.5 4.57186 19.5 6V14C19.5 15.4281 19.4989 16.4492 19.3946 17.2251C19.2922 17.9867 19.0986 18.4367 18.7676 18.7672L18.7672 18.7676C18.4367 19.0986 17.9867 19.2922 17.2251 19.3946C16.4492 19.4989 15.4281 19.5 14 19.5H13C11.5719 19.5 10.5508 19.4989 9.77489 19.3946C9.01331 19.2922 8.56331 19.0986 8.23275 18.7676L8.23235 18.7672C7.90142 18.4367 7.70784 17.9867 7.60541 17.2251C7.50106 16.4492 7.5 15.4281 7.5 14V6C7.5 4.57186 7.50106 3.55082 7.60541 2.77489C7.70784 2.01331 7.90142 1.56331 8.23235 1.23275Z" stroke="currentColor" />
        <path d="M1 9.5L4.5 9.5L6 9.5M6 9.5L5 11ZM6 9.5L5 8Z" fill="currentColor" />
        <path d="M1 9.5L4.5 9.5L6 9.5M6 9.5L5 11M6 9.5L5 8" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    <?php } ?>
    <!-- <img
          src="https://cdn.builder.io/api/v1/image/assets/TEMP/3dc53772cee943a43a10884836e5073da01f81f6926751d4a5e1a64819c317e7?placeholderIfAbsent=true&apiKey=509209c882354b6bbdcc2cde282a5ebb"
          alt="User profile"
          class="sidebar__profile-image"
        /> -->
    <span class="sidebar__text"><?php echo (is_user_logged_in()) ? 'Profile' : 'Login' ?></span>
  </a>
</nav>