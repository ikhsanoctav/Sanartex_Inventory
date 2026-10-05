<script>
/**
 * SANARTEX Realtime AJAX Table & Filter Engine
 * Automatically enables instant debounced search, dropdown filtering, pagination, and KPI syncing.
 */
document.addEventListener('DOMContentLoaded', () => {
    initAjaxFilters();
});

function initAjaxFilters() {
    document.querySelectorAll('form[data-ajax-filter="true"]').forEach(form => {
        if (form._ajaxInitialized) return;
        form._ajaxInitialized = true;

        const targetSelector = form.getAttribute('data-ajax-target') || '#ajax-table-container';
        const searchInputs = form.querySelectorAll('input[type="text"], input[type="search"]');
        const changeInputs = form.querySelectorAll('select, input[type="date"], input[type="radio"], input[type="checkbox"]');
        let currentAbortController = null;
        let debounceTimer = null;

        function setVisualLoading(isLoading) {
            const targets = document.querySelectorAll(targetSelector);
            targets.forEach(el => {
                if (isLoading) {
                    el.classList.add('opacity-60', 'pointer-events-none', 'transition-opacity', 'duration-150');
                } else {
                    el.classList.remove('opacity-60', 'pointer-events-none');
                }
            });

            // Toggle mini spinner in form if present
            const spinners = form.querySelectorAll('.ajax-filter-spinner');
            spinners.forEach(s => s.classList.toggle('hidden', !isLoading));

            const badges = form.querySelectorAll('.ajax-live-badge');
            badges.forEach(b => {
                if (isLoading) {
                    b.classList.remove('hidden');
                    b.innerHTML = '<span class="inline-flex items-center gap-1 text-[10px] text-orange-600 font-semibold animate-pulse"><svg class="animate-spin w-3 h-3 text-orange-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Memfilter data...</span>';
                } else {
                    b.innerHTML = '<span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Realtime</span>';
                }
            });
        }

        function performFetch(customUrl = null) {
            if (currentAbortController) {
                currentAbortController.abort();
            }
            currentAbortController = new AbortController();

            let url;
            if (customUrl) {
                url = customUrl;
            } else {
                const formData = new FormData(form);
                const params = new URLSearchParams();
                for (const [key, value] of formData.entries()) {
                    if (value !== '' && value !== 'all') {
                        params.append(key, value);
                    }
                }
                const baseUrl = form.getAttribute('action') || window.location.pathname;
                const qs = params.toString();
                url = qs ? `${baseUrl}?${qs}` : baseUrl;
            }

            setVisualLoading(true);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: currentAbortController.signal
            })
            .then(res => {
                if (!res.ok) throw new Error('Network error: ' + res.status);
                return res.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newTarget = doc.querySelector(targetSelector);
                const currentTarget = document.querySelector(targetSelector);

                if (newTarget && currentTarget) {
                    currentTarget.innerHTML = newTarget.innerHTML;

                    // Re-bind pagination inside container
                    bindPaginationAndLinks(currentTarget);

                    // Re-initialize Alpine.js if present
                    if (window.Alpine) {
                        try {
                            window.Alpine.initTree(currentTarget);
                        } catch (e) {
                            // ignore
                        }
                    }
                }

                // Sync extra components (KPIs, badges, counter metrics, satuan pills)
                doc.querySelectorAll('[data-ajax-sync]').forEach(newSyncEl => {
                    const syncKey = newSyncEl.getAttribute('data-ajax-sync');
                    const currentSyncEl = document.querySelector(`[data-ajax-sync="${syncKey}"]`);
                    if (currentSyncEl) {
                        currentSyncEl.innerHTML = newSyncEl.innerHTML;
                        bindPaginationAndLinks(currentSyncEl);
                        if (window.Alpine) {
                            try { window.Alpine.initTree(currentSyncEl); } catch(e){}
                        }
                    }
                });

                // Update browser URL
                window.history.replaceState(null, '', url);
                setVisualLoading(false);
            })
            .catch(err => {
                if (err.name !== 'AbortError') {
                    console.error('AJAX Filter Fetch Error:', err);
                    setVisualLoading(false);
                }
            });
        }

        function bindPaginationAndLinks(container) {
            if (!container) return;
            container.querySelectorAll('a.page-link, .pagination a, [data-ajax-link="true"]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const href = link.getAttribute('href');
                    if (href && href !== '#' && !href.startsWith('javascript:')) {
                        performFetch(href);
                    }
                });
            });
        }

        // Live typing with debounce
        searchInputs.forEach(input => {
            input.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    performFetch();
                }, 250);
            });
        });

        // Instant change on selects, dates, radios
        changeInputs.forEach(input => {
            input.addEventListener('change', () => {
                performFetch();
            });
        });

        // Form submit (prevent full reload, run AJAX)
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            performFetch();
        });

        // Reset button handling
        form.querySelectorAll('[data-ajax-reset="true"], a[title="Reset Filter"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                form.reset();
                searchInputs.forEach(i => i.value = '');
                changeInputs.forEach(i => {
                    if (i.tagName === 'SELECT') i.value = 'all';
                    else i.value = '';
                });
                const baseUrl = form.getAttribute('action') || window.location.pathname;
                performFetch(baseUrl);
            });
        });

        // Initial binding for links already on page
        const initialTarget = document.querySelector(targetSelector);
        if (initialTarget) {
            bindPaginationAndLinks(initialTarget);
        }

        // Initial binding for sync elements on page
        document.querySelectorAll('[data-ajax-sync]').forEach(el => {
            bindPaginationAndLinks(el);
        });
    });
}
</script>
