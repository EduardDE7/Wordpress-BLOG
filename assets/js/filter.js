document.addEventListener('DOMContentLoaded', () => {
  const initializeFilter = () => {
    const postsContainer = document.querySelector('.posts-container');
    if (!postsContainer) return;

    const filterType = postsContainer.dataset.filterType || 'category';
    const filterValue = postsContainer.dataset.filterValue || 'all';
    const currentSort =
      document.querySelector('.custom-select__trigger span')?.textContent ||
      'Newest First';

    let currentFilter = {
      type: filterType,
      value: filterValue,
      sort: getSortValue(currentSort),
    };
    let currentPage = 1;
    let isLoading = false;

    const loadMoreButton = document.getElementById('loadMore');
    const filterLinks = document.querySelectorAll('.categories-filter__link');

    if (filterLinks.length > 0) {
      filterLinks.forEach((link) => {
        if (link.dataset.filterValue === currentFilter.value) {
          link.classList.add('active');
        }
      });

      filterLinks.forEach((link) => {
        link.addEventListener('click', function (e) {
          e.preventDefault();
          filterLinks.forEach((link) => link.classList.remove('active'));
          this.classList.add('active');
          currentPage = 1;
          currentFilter = {
            ...currentFilter,
            type: this.dataset.filterType,
            value: this.dataset.filterValue,
          };
          loadPosts();
        });
      });
    }

    if (loadMoreButton) {
      loadMoreButton.addEventListener('click', function () {
        if (!isLoading) {
          currentPage++;
          loadPosts(false);
        }
      });
    }

    const customSelect = document.querySelector('.custom-select');
    const customSelectTrigger = document.querySelector(
      '.custom-select__trigger'
    );

    if (customSelectTrigger) {
      customSelectTrigger.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        customSelect.classList.toggle('open');
      });
    }

    document.addEventListener('click', (e) => {
      if (!customSelect?.contains(e.target)) {
        customSelect?.classList.remove('open');
      }
    });

    const customOptions = document.querySelectorAll('.custom-option');
    customOptions.forEach((option) => {
      option.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (!this.classList.contains('selected')) {
          const selectedOption = this.closest('.custom-options').querySelector(
            '.custom-option.selected'
          );
          if (selectedOption) {
            selectedOption.classList.remove('selected');
          }
          this.classList.add('selected');

          const triggerSpan = customSelectTrigger.querySelector('span');
          triggerSpan.textContent = this.textContent;

          const url = new URL(window.location.href);
          url.searchParams.set('sort', this.dataset.value);
          window.history.pushState({}, '', url);

          currentFilter = {
            ...currentFilter,
            sort: this.dataset.value,
          };
          currentPage = 1;
          loadPosts();
        }

        customSelect.classList.remove('open');
      });
    });

    function loadPosts(replace = true) {
      if (isLoading) return;
      isLoading = true;

      postsContainer.classList.add('loading');
      loadMoreButton.disabled = true;

      const formData = new FormData();
      formData.append(
        'action',
        filterType === 'favorites' ? 'filter_favorite_posts' : 'filter_posts'
      );
      formData.append('nonce', filterAjax.nonce);
      formData.append('filter_type', currentFilter.type);
      formData.append('filter_value', currentFilter.value);
      formData.append('sort', currentFilter.sort);
      formData.append('page', currentPage);

      fetch(filterAjax.ajaxurl, {
        method: 'POST',
        body: formData,
      })
        .then((response) => response.json())
        .then((response) => {
          if (response.success) {
            if (replace) {
              postsContainer.innerHTML = response.data.posts;
            } else {
              postsContainer.insertAdjacentHTML(
                'beforeend',
                response.data.posts
              );
            }

            loadMoreButton.style.display =
              currentPage >= response.data.max_pages ? 'none' : 'block';

            if (typeof window.initializeFavoriteButtons === 'function') {
              window.initializeFavoriteButtons();
            }
          }
        })
        .catch((error) => console.error('Error:', error))
        .finally(() => {
          isLoading = false;
          postsContainer.classList.remove('loading');
          loadMoreButton.disabled = false;
        });
    }

    function getSortValue(label) {
      const sortMap = {
        'Newest First': 'date-desc',
        'Oldest First': 'date-asc',
        'Most Popular': 'popularity',
      };
      return sortMap[label] || 'date-desc';
    }

    loadPosts();
  };

  initializeFilter();
});
