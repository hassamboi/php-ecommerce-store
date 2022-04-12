const signInBtn = document.querySelector(".sign-in-btn");
const logInBtn = document.querySelector(".log-in-btn");
const loginForm = document.querySelector(".login-form");
const signinForm = document.querySelector(".sign-in-form");
const signInPara = document.querySelector(".sign-in-para");
const logInPara = document.querySelector(".log-in-para");

signInBtn.addEventListener("click", () => {
  signinForm.classList.remove("hide-form");
  loginForm.classList.add("hide-form");
  loginForm.classList.add("sign-in-form");
  signInPara.classList.add("login-btn-hide");
  logInPara.classList.remove("login-btn-hide");
});

logInBtn.addEventListener("click", () => {
  signinForm.classList.add("hide-form");
  loginForm.classList.remove("hide-form");
  loginForm.classList.remove("sign-in-form");
  signInPara.classList.remove("login-btn-hide");
  logInPara.classList.add("login-btn-hide");
});
