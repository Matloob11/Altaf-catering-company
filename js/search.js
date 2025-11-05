// Client-side site-wide search
// This script fetches a list of common site pages and searches their text for a query.

(function () {
    // List of pages to search (relative URLs). Add or remove pages as the site evolves.
    const pages = [
        'index.html', 'about.html', 'service.html', 'event.html', 'menu.html', 'book.html',
        'contact.html', 'blog.html', 'team.html', 'testimonial.html', 'pricing.html', '404.html'
    ];

    const form = document.getElementById('siteSearchForm');
    const input = document.getElementById('siteSearchInput');
    const resultsContainer = document.getElementById('searchResults');
    const status = document.getElementById('searchStatus');

    if (!form || !input || !resultsContainer || !status) return;

    function createSnippet(text, idx, q) {
        const radius = 80;
        const start = Math.max(0, idx - radius);
        const end = Math.min(text.length, idx + q.length + radius);
        let snippet = text.substring(start, end).replace(/\s+/g, ' ');
        if (start > 0) snippet = '...' + snippet;
        if (end < text.length) snippet = snippet + '...';
        // highlight the query (basic)
        const regex = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'ig');
        snippet = snippet.replace(regex, '<mark>$1</mark>');
        return snippet;
    }

    async function searchSite(query) {
        const q = query.trim();
        if (!q) {
            status.textContent = 'Enter a keyword to search the site.';
            resultsContainer.innerHTML = '';
            return;
        }

        status.textContent = 'Searching...';
        resultsContainer.innerHTML = '';
        const qLower = q.toLowerCase();

        // Fetch all pages in parallel
        const fetches = pages.map(async (url) => {
            try {
                const resp = await fetch(url, { cache: 'no-store' });
                if (!resp.ok) return null;
                const html = await resp.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const title = (doc.querySelector('title') && doc.querySelector('title').innerText) || url;
                const bodyText = doc.body ? doc.body.innerText : html;
                const bodyLower = bodyText.toLowerCase();
                const idx = bodyLower.indexOf(qLower);
                if (idx !== -1) {
                    const snippet = createSnippet(bodyText, idx, q);
                    return { url, title, snippet, idx };
                }
                return null;
            } catch (err) {
                return null;
            }
        });

        const results = (await Promise.all(fetches)).filter(Boolean);
        if (results.length === 0) {
            status.textContent = 'No results found for "' + q + '"';
            return;
        }

        status.textContent = results.length + (results.length === 1 ? ' result' : ' results') + ' for "' + q + '"';

        // Sort by first occurrence
        results.sort((a, b) => a.idx - b.idx);

        // Render
        resultsContainer.innerHTML = results.map(r => {
            return `
                <div class="col-12 col-md-8">
                    <div class="p-3 border rounded bg-white">
                        <a href="${r.url}" class="h5 d-block mb-1">${r.title}</a>
                        <a href="${r.url}" class="text-primary small mb-2 d-inline-block">${r.url}</a>
                        <p class="mb-0 text-muted small">${r.snippet}</p>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Debounce helper
    function debounce(fn, wait) {
        let t;
        return function (...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    const debouncedSearch = debounce((q) => searchSite(q), 350);

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const q = input.value || '';
        searchSite(q);
    });

    input.addEventListener('input', function (e) {
        const q = e.target.value || '';
        if (q.length < 2) {
            status.textContent = 'Enter at least 2 characters to search.';
            resultsContainer.innerHTML = '';
            return;
        }
        debouncedSearch(q);
    });

    // Optional: focus input when modal shows
    const searchModal = document.getElementById('searchModal');
    if (searchModal) {
        searchModal.addEventListener('shown.bs.modal', function () {
            input.focus();
        });
    }
})();
