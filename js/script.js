document.addEventListener('DOMContentLoaded', () => {
  const loginForm = document.getElementById('login-form');
  const loginButton = document.getElementById('login-button');
  const loading = document.getElementById('loading');

  loginForm.addEventListener('submit', (e) => {
    // Display loading animation
    loading.style.display = 'block';

    // Disable the button to prevent multiple submissions
    loginButton.disabled = true;

    // Simulate a short delay for demo purposes
    setTimeout(() => {
      loginButton.disabled = false; // Re-enable button after form submission
    }, 3000);
  });
});