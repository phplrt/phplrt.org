/**
 * phplrt.org — landing, concept #6 ("re-read")
 *
 *   tabs()    — [data-tabs="id"] + [data-view-key] switch [data-view] blocks.
 *               Two looks share it: the pills above the grammars and the
 *               stage list in the diagnostics band.
 *   copy()    — the install command.
 *   fills()   — meter widths from data-fill, so real phpbench numbers can be
 *               dropped in as plain attributes later.
 *   rail()    — marks the current chapter, and inverts the rail while it is
 *               sitting over a dark band ([data-night]).
 *   rise()    — one reveal, which also starts the line-drawing of a railroad
 *               diagram and the growth of the benchmark bars.
 *
 * The page is complete without this file: code is highlighted in the markup,
 * inactive panels carry the `hidden` attribute themselves, the charts are
 * plotted in the SVG, and every meter has its number written next to it.
 */
(function () {
    'use strict';

    function all(selector, root) {
        return Array.prototype.slice.call((root || document).querySelectorAll(selector));
    }

    /* ------------------------------------------------------------------ */

    function tabs() {
        all('[data-tabs]').forEach(function (bar) {
            var scope = document.getElementById(bar.getAttribute('data-tabs')) || bar;
            var buttons = all('[data-view-key]', bar);

            buttons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var want = button.getAttribute('data-view-key');

                    buttons.forEach(function (other) {
                        var on = other === button;

                        other.classList.toggle('is-on', on);
                        other.setAttribute('aria-selected', on ? 'true' : 'false');
                    });

                    all('[data-view]', scope).forEach(function (view) {
                        view.hidden = view.getAttribute('data-view') !== want;
                    });

                    fills(scope);
                });
            });
        });
    }

    /* ------------------------------------------------------------------ */

    function fills(root) {
        all('[data-fill]', root || document).forEach(function (bar, index) {
            var value = parseFloat(bar.getAttribute('data-fill')) || 0;

            bar.style.transitionDelay = (index % 5) * 60 + 'ms';
            bar.style.width = Math.max(3, Math.min(100, value)) + '%';
        });
    }

    /* ------------------------------------------------------------------ */

    function copy() {
        all('[data-copy]').forEach(function (button) {
            button.addEventListener('click', function () {
                var source = document.querySelector(button.getAttribute('data-copy'));
                var label = button.querySelector('span');

                if (!source || !navigator.clipboard) {
                    return;
                }

                navigator.clipboard.writeText(source.textContent.trim()).then(function () {
                    var was = label ? label.textContent : '';

                    button.classList.add('is-done');

                    if (label) {
                        label.textContent = 'copied';
                    }

                    window.setTimeout(function () {
                        button.classList.remove('is-done');

                        if (label) {
                            label.textContent = was;
                        }
                    }, 1500);
                }, function () {});
            });
        });
    }

    /* ------------------------------------------------------------------ */

    function rail() {
        var nav = document.querySelector('.rail');
        var links = all('.rail a');
        var head = document.querySelector('.top');

        if (!links.length) {
            return;
        }

        var sections = links.map(function (link) {
            return document.getElementById(link.getAttribute('data-spy'));
        });

        var nights = all('[data-night]');
        var ticking = false;

        var update = function () {
            var line = window.scrollY + window.innerHeight * .38;
            var current = 0;

            sections.forEach(function (section, index) {
                if (section && section.offsetTop <= line) {
                    current = index;
                }
            });

            links.forEach(function (link, index) {
                link.classList.toggle('is-here', index === current);
            });

            // The rail is fixed at the middle of the screen — invert it while
            // that middle is inside a dark band
            var middle = window.scrollY + window.innerHeight / 2;
            var onNight = nights.some(function (band) {
                return middle > band.offsetTop && middle < band.offsetTop + band.offsetHeight;
            });

            if (nav) {
                nav.classList.toggle('on-night', onNight);
            }

            if (head) {
                head.classList.toggle('is-off', window.scrollY > 8);
            }

            ticking = false;
        };

        window.addEventListener('scroll', function () {
            if (!ticking) {
                ticking = true;
                window.requestAnimationFrame(update);
            }
        }, { passive: true });

        window.addEventListener('resize', update, { passive: true });
        update();
    }

    /* ------------------------------------------------------------------ */

    function rise() {
        var blocks = all('.rise');

        if (!('IntersectionObserver' in window)) {
            blocks.forEach(function (block) { block.classList.add('is-in'); });
            fills();

            return;
        }

        var watcher = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-in');
                fills(entry.target);
                watcher.unobserve(entry.target);
            });
        }, { threshold: .06, rootMargin: '0px 0px -8% 0px' });

        blocks.forEach(function (block) { watcher.observe(block); });
    }

    function boot() {
        tabs();
        copy();
        rail();
        rise();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
