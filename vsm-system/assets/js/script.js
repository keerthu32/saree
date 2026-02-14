document.addEventListener('DOMContentLoaded', () => {
  const nowEl = document.getElementById('todayDate');
  if (nowEl) {
    nowEl.textContent = new Date().toLocaleDateString(undefined, {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  }
});
