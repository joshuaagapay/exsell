const loginForm = document.querySelector('#login-form');
loginForm.addEventListener('submit', (e) => {
  e.preventDefault();
  let email = loginForm['login-email'].value;
  let password = loginForm['login-password'].value;

  auth.signInWithEmailAndPassword(email, password).then(credentials => {
  	localStorage.setItem('firstLogin', true);
  	window.location.href = "pages/dashboard.php";

  })
});