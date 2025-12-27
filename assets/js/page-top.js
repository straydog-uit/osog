const btn = document.querySelector('[data-js="page-top"]');

btn.addEventListener("click", () => {
  document.documentElement.scrollTo({
    top: 0,
    behavior:"instant",
  });
});