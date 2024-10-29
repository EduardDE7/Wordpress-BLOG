document.addEventListener('DOMContentLoaded', () => {
  if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
    document.documentElement.classList.add('touch-device');
  }

  //#region TOGGLE THEME
  const initThemeToggle = () => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
      document.body.classList.add('dark-theme');
    }

    const themeToggle = document.querySelector('.topbar__theme-toggle');
    if (!themeToggle) return;

    const changeTheme = () => {
      const body = document.body;
      body.classList.toggle('dark-theme');
      localStorage.setItem(
        'theme',
        body.classList.contains('dark-theme') ? 'dark' : 'light'
      );
    };

    themeToggle.addEventListener('click', changeTheme);
  };

  initThemeToggle();
  //#endregion TOGGLE THEME

  //#region EDIT PROFILE
  const initProfileEdit = () => {
    const profileForm = document.querySelector('.profile__form');
    const profileEditButton = document.querySelector('.profile__edit-button');
    if (!profileEditButton || !profileForm) return;

    const profileAvatarUpload = document.querySelector(
      '.profile__avatar-upload'
    );
    const profileInputs = document.querySelectorAll('.profile__input');
    const profileSubmitButton = document.querySelector(
      '.profile__submit-button'
    );
    const avatarInput = document.getElementById('avatar-upload');
    const avatarPreview = document.querySelector('.profile__avatar img');

    let isEditing = false;

    const toggleEditMode = (e) => {
      e.preventDefault();
      isEditing = !isEditing;

      profileInputs.forEach((input) => {
        input.disabled = !isEditing;
      });

      profileAvatarUpload.classList.toggle(
        'profile__avatar-upload--hidden',
        !isEditing
      );

      if (isEditing) {
        profileEditButton.textContent = 'Cancel';
        profileEditButton.classList.add('profile__edit-button--red');
        profileSubmitButton.classList.remove('profile__submit-button--hidden');
      } else {
        profileEditButton.textContent = 'Edit Profile';
        profileEditButton.classList.remove('profile__edit-button--red');
        profileSubmitButton.classList.add('profile__submit-button--hidden');

        profileForm.reset();

        const currentAvatar = avatarPreview.getAttribute('data-original-src');
        if (currentAvatar) {
          avatarPreview.src = currentAvatar;
        }
      }
    };

    if (avatarPreview) {
      avatarPreview.setAttribute('data-original-src', avatarPreview.src);
    }

    if (avatarInput) {
      avatarInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
          if (file.size > 2 * 1024 * 1024) {
            alert('Image size should not exceed 2MB');
            avatarInput.value = '';
            return;
          }

          if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            avatarInput.value = '';
            return;
          }

          const reader = new FileReader();
          reader.onload = (e) => {
            avatarPreview.src = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      });
    }

    profileForm.addEventListener('submit', (e) => {
      if (!isEditing) {
        e.preventDefault();
        return;
      }
    });

    profileEditButton.addEventListener('click', toggleEditMode);
  };

  initProfileEdit();
  //#endregion EDIT PROFILE

  //#region CATEGORIES OPEN/CLOSE

  const initCategoryToggle = () => {
    document.querySelectorAll('.categories__header').forEach((header) => {
      const categorySection = header.closest('.categories__section');
      if (!categorySection) return;

      const categorySlug = categorySection.dataset.category;
      const isOpened = localStorage.getItem(`category_${categorySlug}_opened`);

      if (isOpened === null) {
        categorySection.classList.add('categories__section--opened');
        localStorage.setItem(`category_${categorySlug}_opened`, 'true');
      } else if (isOpened === 'true') {
        categorySection.classList.add('categories__section--opened');
      }

      header.addEventListener('click', () => {
        categorySection.classList.toggle('categories__section--opened');

        const isNowOpened = categorySection.classList.contains(
          'categories__section--opened'
        );
        localStorage.setItem(`category_${categorySlug}_opened`, isNowOpened);
      });
    });
  };

  initCategoryToggle();

  //#endregion CATEGORIES OPEN/CLOSE

  //#region CATEGORIES SLIDER
  const initSliders = () => {
    document.querySelectorAll('[data-slider]').forEach((slider) => {
      const container = slider.querySelector('.slider__container');
      const prevBtn = slider.querySelector('[data-slider-prev]');
      const nextBtn = slider.querySelector('[data-slider-next]');

      if (!container || !prevBtn || !nextBtn) return;

      const updateArrowsVisibility = () => {
        const isStart = container.scrollLeft === 0;
        const isEnd =
          container.scrollLeft + container.clientWidth >=
          container.scrollWidth - 1;

        prevBtn.classList.toggle('slider__button--hidden', isStart);
        nextBtn.classList.toggle('slider__button--hidden', isEnd);
      };

      prevBtn.addEventListener('click', () => {
        container.scrollBy({
          left: -container.offsetWidth,
          behavior: 'smooth',
        });
      });

      nextBtn.addEventListener('click', () => {
        container.scrollBy({ left: container.offsetWidth, behavior: 'smooth' });
      });

      container.addEventListener('scroll', updateArrowsVisibility);
      updateArrowsVisibility();

      let resizeTimer;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(updateArrowsVisibility, 100);
      });
    });
  };
  initSliders();
  //#endregion CATEGORIES SLIDER

  //#region CUSTOM SELECT
  const initCustomSelect = () => {
    const customSelectWrapper = document.querySelector(
      '.custom-select-wrapper'
    );
    const customSelect = document.querySelector('.custom-select');
    const customOptions = document.querySelectorAll('.custom-option');

    if (!customSelectWrapper || !customSelect || customOptions.length === 0)
      return;

    customSelectWrapper.addEventListener('click', function () {
      customSelect.classList.toggle('open');
    });

    customOptions.forEach((option) => {
      option.addEventListener('click', function () {
        if (!this.classList.contains('selected')) {
          const selectedOption = this.parentNode.querySelector(
            '.custom-option.selected'
          );
          if (selectedOption) {
            selectedOption.classList.remove('selected');
          }
          this.classList.add('selected');

          const triggerSpan = this.closest('.custom-select').querySelector(
            '.custom-select__trigger span'
          );
          if (triggerSpan) {
            triggerSpan.textContent = this.textContent;
          }

          const currentUrl = new URL(window.location.href);
          currentUrl.searchParams.set('sort', this.getAttribute('data-value'));
          window.location.href = currentUrl.toString();
        }
      });
    });

    window.addEventListener('click', function (e) {
      if (customSelect && !customSelect.contains(e.target)) {
        customSelect.classList.remove('open');
      }
    });
  };
  initCustomSelect();
  //#endregion CUSTOM SELECT

  //#region SIDEBAR TOGGLE
  const initSidebarToggle = () => {
    const sidebar = document.querySelector('.post__sidebar');
    const trigger = document.querySelector('.post__sidebar-trigger');

    if (!sidebar || !trigger) return;

    trigger.addEventListener('click', () => {
      sidebar.classList.toggle('active');
    });

    document.addEventListener('click', (event) => {
      if (!sidebar.contains(event.target) && !trigger.contains(event.target)) {
        sidebar.classList.remove('active');
      }
    });
  };
  initSidebarToggle();
  //#endregion SIDEBAR TOGGLE

  //#region SCROLL TO TOP
  const initScrollToTopButton = () => {
    const scrollToTopButton = document.getElementById('scrollToTop');
    if (!scrollToTopButton) return;

    let lastScrollTop = 0;

    window.addEventListener('scroll', function () {
      const currentScroll =
        window.scrollY || document.documentElement.scrollTop;

      if (currentScroll > 400) {
        scrollToTopButton.classList.add('visible');

        if (currentScroll > lastScrollTop) {
          scrollToTopButton.classList.remove('visible');
        } else {
          scrollToTopButton.classList.add('visible');
        }
      } else {
        scrollToTopButton.classList.remove('visible');
      }

      lastScrollTop = currentScroll;
    });

    scrollToTopButton.addEventListener('click', function () {
      window.scrollTo({
        top: 0,
        behavior: 'smooth',
      });
    });
  };
  initScrollToTopButton();
  //#endregion SCROLL TO TOP
});
