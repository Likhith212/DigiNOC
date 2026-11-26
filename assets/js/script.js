document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.password-toggle').forEach(button => {
    button.addEventListener('click', () => {
      const input = button.parentElement.querySelector('input');
      input.type = input.type === 'password' ? 'text' : 'password';
      button.innerHTML = input.type === 'password' ? '<i class="fa-solid fa-eye"></i>' : '<i class="fa-solid fa-eye-slash"></i>';
    });
  });
  const navToggle = document.querySelector('.nav-toggle');
  const landingNav = document.querySelector('.landing-nav');
  if (navToggle) navToggle.addEventListener('click', () => landingNav.classList.toggle('open'));
  const menuToggle = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');
  if (menuToggle) menuToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
  document.querySelectorAll('.flash-message').forEach(message => {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message.textContent;
    toast.style.cssText = 'position:fixed;right:20px;bottom:20px;background:#15304f;border:1px solid #315b8e;border-radius:8px;padding:12px 16px;z-index:100';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
  });
});
