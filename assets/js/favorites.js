document.addEventListener('DOMContentLoaded', () => {
  const initializeFavoriteButtons = () => {
    const favoriteButtons = document.querySelectorAll('.favorite-button');

    if (!favoriteButtons.length) return;

    favoriteButtons.forEach((button) => {
      if (!button.hasAttribute('data-initialized')) {
        button.setAttribute('data-initialized', 'true');
        button.addEventListener('click', handleFavoriteClick);
      }
    });
  };

  const handleFavoriteClick = async (e) => {
    e.preventDefault();
    const button = e.currentTarget;
    const postId = button.dataset.postId;

    if (!postId) return;

    try {
      button.disabled = true;
      const formData = new FormData();
      formData.append('action', 'handle_favorite');
      formData.append('post_id', postId);
      formData.append('nonce', favoritesAjax.nonce);

      const response = await fetch(favoritesAjax.ajaxurl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
      });

      const data = await response.json();

      console.log('Response received:', data);

      if (data.success) {
        button.classList.toggle('active');
      } else {
        throw new Error(data.data || 'Unknown error');
      }
    } catch (error) {
      console.error('Error occurred:', error);
    } finally {
      button.disabled = false;
    }
  };
  initializeFavoriteButtons();
  window.initializeFavoriteButtons = initializeFavoriteButtons;
});
