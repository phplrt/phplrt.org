/**
 * phplrt.org — documentation pages
 *
 *   nav()    — collapsible chapters, remembered per category in localStorage.
 *   find()   — the search field, talking to /api/search.json.
 *   drawer() — the sidebar as a sheet on narrow screens.
 *
 * Written in the same plain style as the landing sheet: no framework, and
 * every page still readable with this file missing — the chapters render
 * open, and the search field simply does nothing.
 */
(function () {
    'use strict';

    var MIN_QUERY = 2;
    var DEBOUNCE = 300;

    function all(selector, root) {
        return Array.prototype.slice.call((root || document).querySelectorAll(selector));
    }

    function text(value) {
        var node = document.createElement('div');

        node.textContent = value == null ? '' : String(value);

        return node.innerHTML;
    }

    /* ------------------------------------------------------------------ */

    function nav() {
        all('[data-nav-group]').forEach(function (group) {
            var button = group.querySelector('[data-nav-toggle]');
            var key = 'menu-shown-' + group.getAttribute('data-nav-group');

            if (!button) {
                return;
            }

            var apply = function (open) {
                group.classList.toggle('is-shut', !open);
                button.setAttribute('aria-expanded', open ? 'true' : 'false');
            };

            // Anything not explicitly closed before is open, which matches
            // what the markup renders without this script.
            apply(window.localStorage.getItem(key) !== '0');

            button.addEventListener('click', function () {
                var open = button.getAttribute('aria-expanded') !== 'true';

                window.localStorage.setItem(key, open ? '1' : '0');
                apply(open);
            });
        });
    }

    /* ------------------------------------------------------------------ */

    function find() {
        var root = document.querySelector('[data-find]');

        if (!root) {
            return;
        }

        var input = root.querySelector('[data-find-input]');
        var out = root.querySelector('[data-find-out]');
        var timer = null;
        var seq = 0;

        var show = function (html) {
            out.innerHTML = html;
            out.hidden = false;
        };

        var hide = function () {
            out.hidden = true;
            out.innerHTML = '';
        };

        var render = function (items) {
            return items.map(function (item) {
                var title = text(item.title);

                // The API reports which fragments matched; mark them up
                // after escaping, so the payload can never inject markup.
                (item.found || []).forEach(function (match) {
                    var needle = text(match);

                    if (needle !== '') {
                        title = title.split(needle).join('<span>' + needle + '</span>');
                    }
                });

                return '<a class="find__hit" href="' + text(item.url) + '">' +
                    '<b>' + title + '</b>' +
                    '<i>' + text(item.page) + '</i>' +
                    '</a>';
            }).join('');
        };

        var run = function (query) {
            var mine = ++seq;

            window.fetch('/api/search.json', {
                method: 'POST',
                headers: {
                    'content-type': 'application/json',
                    'accept': 'application/json'
                },
                body: JSON.stringify({ query: query })
            }).then(function (response) {
                return response.json();
            }).then(function (result) {
                // A slower earlier request must not overwrite a newer answer
                if (mine !== seq) {
                    return;
                }

                if (result.error) {
                    throw new Error(result.error);
                }

                if (!result.data || result.data.length === 0) {
                    show('<div class="find__note">Nothing found</div>');

                    return;
                }

                show(render(result.data));
            }).catch(function (error) {
                if (mine === seq) {
                    show('<div class="find__note find__note--bad">Search is unavailable</div>');
                    window.console && console.error(error);
                }
            });
        };

        input.addEventListener('input', function () {
            var query = input.value.trim();

            window.clearTimeout(timer);

            if (query.length < MIN_QUERY) {
                seq++;
                hide();

                return;
            }

            timer = window.setTimeout(function () { run(query); }, DEBOUNCE);
        });

        input.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                input.value = '';
                seq++;
                hide();
            }
        });

        document.addEventListener('click', function (event) {
            if (!root.contains(event.target)) {
                hide();
            }
        });
    }

    /* ------------------------------------------------------------------ */

    function drawer() {
        var button = document.querySelector('[data-docs-toggle]');
        var side = document.getElementById('docs-side');

        if (!button || !side) {
            return;
        }

        var set = function (open) {
            side.classList.toggle('is-open', open);
            button.setAttribute('aria-expanded', open ? 'true' : 'false');
        };

        button.addEventListener('click', function () {
            set(button.getAttribute('aria-expanded') !== 'true');
        });

        document.addEventListener('click', function (event) {
            if (side.classList.contains('is-open') &&
                !side.contains(event.target) &&
                !button.contains(event.target)) {
                set(false);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                set(false);
            }
        });

        // Following a link inside the sheet should close it
        side.addEventListener('click', function (event) {
            if (event.target.closest('a')) {
                set(false);
            }
        });
    }

    function boot() {
        nav();
        find();
        drawer();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
