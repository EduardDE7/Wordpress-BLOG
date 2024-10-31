document.addEventListener('DOMContentLoaded', () => {
  const initializeFilter = () => {
    const postsContainer = document.querySelector('.posts-container');
    if (!postsContainer) return;

    const filterType = postsContainer.dataset.filterType || 'category';
    const filterValue = postsContainer.dataset.filterValue || 'all';

    let currentFilter = {
      type: filterType,
      value: filterValue,
    };
    let currentPage = 1;
    let isLoading = false;

    const loadMoreButton = document.getElementById('loadMore');
    if (!loadMoreButton) return;

    const filterLinks = document.querySelectorAll('.categories-filter__link');
    if (filterLinks.length === 0) return;

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
          type: this.dataset.filterType,
          value: this.dataset.filterValue,
        };
        loadPosts();
      });
    });

    loadMoreButton.addEventListener('click', function () {
      if (!isLoading) {
        currentPage++;
        loadPosts(false);
      }
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

    loadPosts();
  };

  initializeFilter();
});
