document.addEventListener('DOMContentLoaded', () => {
  if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
    document.documentElement.classList.add('touch-device');
  }

  //#region TOGGLE THEME
  const themeToggle = document.querySelector('.topbar__theme-toggle');

  function changeTheme() {
    const body = document.body;
    body.classList.toggle('dark-theme');

    if (body.classList.contains('dark-theme')) {
      localStorage.setItem('theme', 'dark');
    } else {
      localStorage.setItem('theme', 'light');
    }
  }

  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    document.body.classList.add('dark-theme');
  }

  if (themeToggle) {
    themeToggle.addEventListener('click', changeTheme);
  } else {
    console.error('Theme toggle button not found');
  }

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

    let isEditing = false;
    function toggleEditMode() {
      isEditing = !isEditing;

      profileInput.forEach((input) => {
        input.disabled = !isEditing;
      });

      if (isEditing) {
        profileAvatarUpload.classList.remove('profile__avatar-upload--hidden');
        profileSubmitButton.classList.remove('profile__submit-button--hidden');
        profileEditButton.classList.add('profile__edit-button--red');
        profileEditButton.textContent = 'Cancel';
      } else {
        profileAvatarUpload.classList.add('profile__avatar-upload--hidden');
        profileSubmitButton.classList.add('profile__submit-button--hidden');
        profileEditButton.classList.remove('profile__edit-button--red');
      }
    }

    profileEditButton.addEventListener('click', toggleEditMode);

    const avatarInput = document.getElementById('avatar-upload');
    const avatarPreview = document.querySelector('.profile__avatar img');

    avatarInput.addEventListener('change', function (e) {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
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
  document
    .querySelector('.custom-select-wrapper')
    .addEventListener('click', function () {
      const customSelect = document.querySelector('.custom-select');
      customSelect.classList.toggle('open');
    });

  for (const option of document.querySelectorAll('.custom-option')) {
    option.addEventListener('click', function () {
      if (!option.classList.contains('selected')) {
        const selectedOption = option.parentNode.querySelector(
          '.custom-option.selected'
        );
        if (selectedOption) {
          selectedOption.classList.remove('selected');
        }
        option.classList.add('selected');

        const triggerSpan = option
          .closest('.custom-select')
          .querySelector('.custom-select__trigger span');
        triggerSpan.textContent = option.textContent;

        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('sort', option.getAttribute('data-value'));
        window.location.href = currentUrl.toString();
      }
    });
  }

  window.addEventListener('click', function (e) {
    const select = document.querySelector('.custom-select');
    if (!select.contains(e.target)) {
      select.classList.remove('open');
    }
  });

  // #endregion CUSTOM SELECT
});
