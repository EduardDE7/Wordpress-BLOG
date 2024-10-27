document.addEventListener('DOMContentLoaded', function () {
  const favoriteButtons = document.querySelectorAll('.favorite-button');

  favoriteButtons.forEach((button) => {
    button.addEventListener('click', handleFavoriteClick);
  });

  async function handleFavoriteClick(e) {
    e.preventDefault();
    const button = e.currentTarget;
    const postId = button.dataset.postId;

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

        // showNotification(data.data.message);
      } else {
        throw new Error(data.data || 'Unknown error');
      }
    } catch (error) {
      console.error('Error occurred:', error);
      // showNotification(
      //   'Error: ' +
      //     (error.message || 'An error occurred. Please try again later.'),
      //   'error'
      // );
    } finally {
      button.disabled = false;
    }
  }

  // function showNotification(message, type = 'success') {
  //   const notification = document.createElement('div');
  //   notification.className = `favorite-notification ${type}`;
  //   notification.textContent = message;

  //   notification.style.position = 'fixed';
  //   notification.style.top = '20px';
  //   notification.style.right = '20px';
  //   notification.style.padding = '15px 25px';
  //   notification.style.backgroundColor =
  //     type === 'success' ? '#4CAF50' : '#f44336';
  //   notification.style.color = 'white';
  //   notification.style.borderRadius = '18px';
  //   notification.style.zIndex = '1000';
  //   notification.style.opacity = '0';
  //   notification.style.transition = 'opacity 0.3s ease-in-out';

  //   document.body.appendChild(notification);

  //   setTimeout(() => {
  //     notification.style.opacity = '1';
  //   }, 10);

  //   setTimeout(() => {
  //     notification.style.opacity = '0';
  //     setTimeout(() => {
  //       document.body.removeChild(notification);
  //     }, 300);
  //   }, 3000);
  // }
});
