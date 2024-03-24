document.addEventListener("DOMContentLoaded", function () {
  var sidebar = document.getElementById("dashboard_sidebar");
  var content = document.getElementById("dashboard_content");
  var toggleButton = document.getElementById("toggle_menu");
  toggleButton.addEventListener("click", function () {
    sidebar.classList.toggle("hide");
    if (sidebar.classList.contains("hide")) {
      content.style.marginLeft = "250px";
    } else {
      content.style.marginLeft = "0px";
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const togglePassword = document.getElementById("togglePassword");
  const toggleConfirmPassword = document.getElementById(
    "toggleConfirmPassword"
  );
  const passwordInput = document.getElementById("password");
  const confirmPasswordInput = document.getElementById("confirmPassword");

  togglePassword.addEventListener("click", function () {
    const type =
      passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);
    this.querySelector("i").classList.toggle("fa-eye-slash");
    this.querySelector("i").classList.toggle("fa-eye");
  });

  toggleConfirmPassword.addEventListener("click", function () {
    const type =
      confirmPasswordInput.getAttribute("type") === "password"
        ? "text"
        : "password";
    confirmPasswordInput.setAttribute("type", type);
    this.querySelector("i").classList.toggle("fa-eye-slash");
    this.querySelector("i").classList.toggle("fa-eye");
  });
});
