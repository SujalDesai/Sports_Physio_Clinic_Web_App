document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    const pwd = form.querySelector("input[type='password']")?.value;
    if (pwd) {
      const regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;
      if (!regex.test(pwd)) {
        alert("Password must be at least 8 characters, include a number, a letter, and a special character.");
        e.preventDefault();
      }
    }
  });
});
