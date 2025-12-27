// onkeyup on search box: focus mode for result container
function focusResultContainer() {
  var value = document.getElementById('search-input').value;

  if (value.length > 0) {
    document.getElementById('HHHH').setAttribute('style', 'visibility: hidden');
    document.getElementById('result-container').setAttribute('style', 'display: block');
  }
  else {
    document.getElementById('HHHH').setAttribute('style', 'visibility: visible');
    document.getElementById('result-container').setAttribute('style', 'display: none');
  }
}

// redirect to result page
function redirectToResultPage() {
  if (document.getElementById('search-input').value != '') {
    var content = document.getElementById('result-container').innerHTML; // The innerHTML property returns the HTML content
    window.sessionStorage.setItem('key', content);

    document.getElementById('search-input').value = ''; // clear input field
    document.getElementById('HHHH').setAttribute('style', 'visibility: visible'); // return to visible
    document.getElementById('result-container').setAttribute('style', 'display: none'); // return to none

    // redirecting...
    window.location.href = "/results/";
  }
  return false;

}

document.getElementById("search-input").addEventListener("input", focusResultContainer);

document.querySelector(".search-box").addEventListener("submit", event => {
  event.preventDefault();
  redirectToResultPage();
});