document.addEventListener('DOMContentLoaded', () => {
  if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
    document.documentElement.classList.add('touch-device');
  }

  //#region TOGGLE THEME
  const themeToggle = document.querySelector('.topbar__theme-toggle');

  const changeTheme = () => {
    const body = document.body;
    body.classList.toggle('dark-theme');
    localStorage.setItem(
      'theme',
      body.classList.contains('dark-theme') ? 'dark' : 'light'
    );
  };

  const applySavedTheme = () => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
      document.body.classList.add('dark-theme');
    }
  };

  if (themeToggle) {
    themeToggle.addEventListener('click', changeTheme);
  } else {
    console.error('Theme toggle button not found');
  }

  applySavedTheme();
  //#endregion TOGGLE THEME

  //#region EDIT PROFILE
  const profileEditButton = document.querySelector('.profile__edit-button');

  if (profileEditButton) {
    const profileAvatarUpload = document.querySelector(
      '.profile__avatar-upload'
    );
    const profileInput = document.querySelectorAll('.profile__input');
    const profileSubmitButton = document.querySelector(
      '.profile__submit-button'
    );

    const toggleEditMode = () => {
      const isEditing = !profileInput[0].disabled; // Проверяем состояние редактирования
      profileInput.forEach((input) => {
        input.disabled = isEditing;
      });

      profileAvatarUpload.classList.toggle(
        'profile__avatar-upload--hidden',
        !isEditing
      );
      profileSubmitButton.classList.toggle(
        'profile__submit-button--hidden',
        !isEditing
      );
      profileEditButton.classList.toggle(
        'profile__edit-button--red',
        isEditing
      );
      profileEditButton.textContent = isEditing ? 'Cancel' : 'Edit';
    };

    profileEditButton.addEventListener('click', toggleEditMode);

    const avatarInput = document.getElementById('avatar-upload');
    const avatarPreview = document.querySelector('.profile__avatar img');

    avatarInput.addEventListener('change', (e) => {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
          avatarPreview.src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  }
  //#endregion EDIT PROFILE

  //#region CATEGORIES SLIDER
  function initSliders() {
    document.querySelectorAll('[data-slider]').forEach((slider) => {
      const container = slider.querySelector('.slider__container');
      const prevBtn = slider.querySelector('[data-slider-prev]');
      const nextBtn = slider.querySelector('[data-slider-next]');

      function updateArrowsVisibility() {
        const isStart = container.scrollLeft === 0;
        const isEnd =
          container.scrollLeft + container.clientWidth >=
          container.scrollWidth - 1;

        prevBtn.classList.toggle('slider__button--hidden', isStart);
        nextBtn.classList.toggle('slider__button--hidden', isEnd);
      }

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
  }

  initSliders();

  document.querySelectorAll('.categories__header').forEach((header) => {
    const categorySection = header.closest('.categories__section');
    const categorySlug = categorySection.dataset.category;

    const isCollapsed =
      localStorage.getItem(`category_${categorySlug}_collapsed`) === 'true';
    if (isCollapsed) {
      categorySection.classList.add('categories__section--collapsed');
    }

    header.addEventListener('click', () => {
      categorySection.classList.toggle('categories__section--collapsed');

      const isNowCollapsed = categorySection.classList.contains(
        'categories__section--collapsed'
      );
      localStorage.setItem(
        `category_${categorySlug}_collapsed`,
        isNowCollapsed
      );
    });
  });

  //#endregion CATEGORIES SLIDER

  //#region CUSTOM SELECT
  // Custom Select functionality
  const customSelectWrapper = document.querySelector('.custom-select-wrapper');
  const customSelect = document.querySelector('.custom-select');
  const customOptions = document.querySelectorAll('.custom-option');

  if (customSelectWrapper && customSelect && customOptions.length > 0) {
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
  }
  //#endregion CUSTOM SELECT
});
