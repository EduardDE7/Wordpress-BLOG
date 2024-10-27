document.addEventListener('DOMContentLoaded', function () {
  function initializeReactions(container) {
    const postId = container.dataset.postId;
    const likeBtn = container.querySelector('.like-button');
    const dislikeBtn = container.querySelector('.dislike-button');
    const likesCount = container.querySelector('.likes-count');
    const dislikesCount = container.querySelector('.dislikes-count');

    function getSavedReaction() {
      const saved = localStorage.getItem('postReactions');
      if (!saved) return 'none';

      const reactions = JSON.parse(saved);
      return reactions[postId] || 'none';
    }

    function saveReaction(type) {
      const saved = localStorage.getItem('postReactions');
      const reactions = saved ? JSON.parse(saved) : {};

      if (type === 'none') {
        delete reactions[postId];
      } else {
        reactions[postId] = type;
      }

      localStorage.setItem('postReactions', JSON.stringify(reactions));
    }

    function updateButtonStates(activeType) {
      likeBtn.classList.toggle('active', activeType === 'like');
      dislikeBtn.classList.toggle('active', activeType === 'dislike');
    }

    function handleReaction(clickedType) {
      const previousType = getSavedReaction();
      const newType = previousType === clickedType ? 'none' : clickedType;

      fetch(reactionsAjax.ajaxurl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          action: 'post_reaction',
          post_id: postId,
          type: newType,
          previousType: previousType,
          nonce: reactionsAjax.nonce,
        }),
      })
        .then((response) => response.json())
        .then((result) => {
          if (result.success) {
            likesCount.textContent = result.data.likes;
            dislikesCount.textContent = result.data.dislikes;
            saveReaction(newType);
            updateButtonStates(newType);

            const activeButton = newType === 'like' ? likeBtn : dislikeBtn;
            if (newType !== 'none') {
              activeButton.classList.add('pulse');
              setTimeout(() => {
                activeButton.classList.remove('pulse');
              }, 300);
            }
          }
        })
        .catch((error) => {
          console.error('Error handling reaction:', error);
        });
    }

    function bindEvents() {
      likeBtn.addEventListener('click', () => handleReaction('like'));
      dislikeBtn.addEventListener('click', () => handleReaction('dislike'));
    }

    function initializeState() {
      const savedReaction = getSavedReaction();
      updateButtonStates(savedReaction);
    }

    initializeState();
    bindEvents();
  }

  document.querySelectorAll('.post-reactions').forEach((container) => {
    initializeReactions(container);
  });
});
