const logout = document.querySelector('#log-out');
logout.addEventListener('click', (e) => {
  e.preventDefault();
  auth.signOut().then(() => {
    window.location.href = "../index.html";
    localStorage.clear();
  })
})