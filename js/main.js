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

  //#region EXPAND CATEGORIES

  const categoriesToggle = document.getElementById('categories-toggle');
  const categoriesList = document.getElementById('categories-list');
  const hiddenCategories = document.querySelectorAll(
    '.categories__link--hidden'
  );

  function hideCategories() {
    categoriesList.classList.remove('categories__list--full');
    categoriesToggle.classList.remove('categories__toggle--rotate');
    hiddenCategories.forEach((category) => {
      category.classList.add('categories__link--hidden');
    });
  }

  if (categoriesToggle) {
    categoriesToggle.addEventListener('click', function (event) {
      event.stopPropagation();
      categoriesList.classList.toggle('categories__list--full');
      categoriesToggle.classList.toggle('categories__toggle--rotate');
      hiddenCategories.forEach((category) => {
        category.classList.toggle('categories__link--hidden');
      });
    });
    document.addEventListener('click', hideCategories);
  }

  //#endregion EXPAND CATEGORIES

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

  document.querySelectorAll('.slider').forEach((slider) => {
    const container = slider.querySelector('.slider__container');
    const prevBtn = slider.querySelector('.slider__button--prev');
    const nextBtn = slider.querySelector('.slider__button--next');

    function updateArrowsVisibility() {
      const isStart = container.scrollLeft === 0;
      const isEnd =
        container.scrollLeft + container.clientWidth >=
        container.scrollWidth - 1;

      if (isStart) {
        prevBtn.classList.add('slider__button--hidden');
      } else {
        prevBtn.classList.remove('slider__button--hidden');
      }

      if (isEnd) {
        nextBtn.classList.add('slider__button--hidden');
      } else {
        nextBtn.classList.remove('slider__button--hidden');
      }
    }

    prevBtn.addEventListener('click', () => {
      container.scrollBy({ left: -1, behavior: 'smooth' });
    });

    nextBtn.addEventListener('click', () => {
      container.scrollBy({ left: 1, behavior: 'smooth' });
    });

    container.addEventListener('scroll', updateArrowsVisibility);
    updateArrowsVisibility();

    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(updateArrowsVisibility, 100);
    });
  });
  //#endregion CATEGORIES SLIDER
});
