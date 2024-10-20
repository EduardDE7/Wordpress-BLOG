document.addEventListener('DOMContentLoaded', () => {
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
  const tabs = document.querySelectorAll('.authorize__tab');
  const tabContents = document.querySelectorAll('.authorize__pane');

  tabs.forEach((tab) => {
    tab.addEventListener('click', function (e) {
      e.preventDefault();
      const tabId = this.getAttribute('data-tab');

      // Remove active class from all tabs and tab contents
      tabs.forEach((t) => t.classList.remove('authorize__tab--active'));
      tabContents.forEach((c) => c.classList.remove('authorize__pane--active'));

      // Add active class to current tab and its content
      this.classList.add('authorize__tab--active');
      document.getElementById(tabId).classList.add('authorize__pane--active');
    });
  });
});
