
// Login form hide pwd toggle
function handlePasswordtoggle() {
  const togglePassword = document.querySelector('.toggle-pwd');

  if(!togglePassword) {
    return;
  }

  togglePassword.addEventListener('click', () => {
    let inputField = togglePassword.previousElementSibling.previousElementSibling;
    const type = inputField.getAttribute("type") === "password" ? "text" : "password";
    inputField.setAttribute("type", type);
    // Toggle Icon
    togglePassword.classList.toggle("icon-eye-slash");
  });
}
handlePasswordtoggle();

