function initSJS() {
  SimpleJekyllSearch({ // Configuration SJS
    // required
    searchInput: document.getElementById('search-input'),
    resultsContainer: document.getElementById('result-container'),
    json: '/assets/js/search.json',

    // optional
    fuzzy: true,
    noResultsText: 'No results found'
  })
}