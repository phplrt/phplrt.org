(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./resources/assets/app.js":
/*!*********************************!*\
  !*** ./resources/assets/app.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _styles_site_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./styles/site.css */ "./resources/assets/styles/site.css");
/* harmony import */ var _styles_docs_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./styles/docs.css */ "./resources/assets/styles/docs.css");
/* harmony import */ var _site_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./site.js */ "./resources/assets/site.js");
/* harmony import */ var _site_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_site_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _docs_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./docs.js */ "./resources/assets/docs.js");
/* harmony import */ var _docs_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_docs_js__WEBPACK_IMPORTED_MODULE_3__);
// The landing sheet and its behaviour are taken from the concept as they were
// authored. Everything documentation-specific lives beside them and is written
// against the same tokens and the same plain-JS style.





/***/ }),

/***/ "./resources/assets/docs.js":
/*!**********************************!*\
  !*** ./resources/assets/docs.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(/*! core-js/modules/es.array.for-each.js */ "./node_modules/core-js/modules/es.array.for-each.js");
__webpack_require__(/*! core-js/modules/es.array.join.js */ "./node_modules/core-js/modules/es.array.join.js");
__webpack_require__(/*! core-js/modules/es.array.map.js */ "./node_modules/core-js/modules/es.array.map.js");
__webpack_require__(/*! core-js/modules/es.array.slice.js */ "./node_modules/core-js/modules/es.array.slice.js");
__webpack_require__(/*! core-js/modules/es.date.to-json.js */ "./node_modules/core-js/modules/es.date.to-json.js");
__webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
__webpack_require__(/*! core-js/modules/es.promise.js */ "./node_modules/core-js/modules/es.promise.js");
__webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");
__webpack_require__(/*! core-js/modules/es.string.split.js */ "./node_modules/core-js/modules/es.string.split.js");
__webpack_require__(/*! core-js/modules/es.string.trim.js */ "./node_modules/core-js/modules/es.string.trim.js");
__webpack_require__(/*! core-js/modules/web.dom-collections.for-each.js */ "./node_modules/core-js/modules/web.dom-collections.for-each.js");
__webpack_require__(/*! core-js/modules/web.timers.js */ "./node_modules/core-js/modules/web.timers.js");
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
      var apply = function apply(open) {
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
    var show = function show(html) {
      out.innerHTML = html;
      out.hidden = false;
    };
    var hide = function hide() {
      out.hidden = true;
      out.innerHTML = '';
    };
    var render = function render(items) {
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
        return '<a class="find__hit" href="' + text(item.url) + '">' + '<b>' + title + '</b>' + '<i>' + text(item.page) + '</i>' + '</a>';
      }).join('');
    };
    var run = function run(query) {
      var mine = ++seq;
      window.fetch('/api/search.json', {
        method: 'POST',
        headers: {
          'content-type': 'application/json',
          'accept': 'application/json'
        },
        body: JSON.stringify({
          query: query
        })
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
      })["catch"](function (error) {
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
      timer = window.setTimeout(function () {
        run(query);
      }, DEBOUNCE);
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
    var set = function set(open) {
      side.classList.toggle('is-open', open);
      button.setAttribute('aria-expanded', open ? 'true' : 'false');
    };
    button.addEventListener('click', function () {
      set(button.getAttribute('aria-expanded') !== 'true');
    });
    document.addEventListener('click', function (event) {
      if (side.classList.contains('is-open') && !side.contains(event.target) && !button.contains(event.target)) {
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

/***/ }),

/***/ "./resources/assets/site.js":
/*!**********************************!*\
  !*** ./resources/assets/site.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(/*! core-js/modules/es.array.for-each.js */ "./node_modules/core-js/modules/es.array.for-each.js");
__webpack_require__(/*! core-js/modules/es.array.map.js */ "./node_modules/core-js/modules/es.array.map.js");
__webpack_require__(/*! core-js/modules/es.array.slice.js */ "./node_modules/core-js/modules/es.array.slice.js");
__webpack_require__(/*! core-js/modules/es.array.some.js */ "./node_modules/core-js/modules/es.array.some.js");
__webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
__webpack_require__(/*! core-js/modules/es.parse-float.js */ "./node_modules/core-js/modules/es.parse-float.js");
__webpack_require__(/*! core-js/modules/es.string.trim.js */ "./node_modules/core-js/modules/es.string.trim.js");
__webpack_require__(/*! core-js/modules/web.dom-collections.for-each.js */ "./node_modules/core-js/modules/web.dom-collections.for-each.js");
__webpack_require__(/*! core-js/modules/web.timers.js */ "./node_modules/core-js/modules/web.timers.js");
/**
 * phplrt.org — landing, concept #6 ("re-read")
 *
 *   tabs()    — [data-tabs="id"] + [data-view-key] switch [data-view] blocks.
 *               Two looks share it: the pills above the grammars and the
 *               stage list in the diagnostics band. The incoming block is
 *               marked .is-swap for the length of its entrance.
 *   copy()    — the install command.
 *   fills()   — meter widths from data-fill, so real phpbench numbers can be
 *               dropped in as plain attributes later.
 *   rail()    — marks the current chapter, and inverts the rail while it is
 *               sitting over a dark band ([data-night]).
 *   rise()    — one reveal. Everything else in section 19 of the sheet hangs
 *               off the .is-in it puts on a block: the sequence its children
 *               arrive in, the drawing of a railroad diagram and of the five
 *               benchmark series, and the meters below them.
 *
 * The page is complete without this file: code is highlighted in the markup,
 * inactive panels carry the `hidden` attribute themselves, the charts are
 * plotted in the SVG, and every meter has its number written next to it. The
 * reveals hide nothing until the inline script in <head> has put `js` on the
 * root, so a page that never runs any of this is simply already there.
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
            var show = view.getAttribute('data-view') === want;
            var shown = !view.hidden;
            view.hidden = !show;

            // A view arriving by click gets the same short entrance
            // the scroll gives a block, and its railroad diagram is
            // drawn again — the panel around it was revealed long
            // ago, so nothing else would move. Dropping the class
            // and reading a layout property restarts the animation
            // on a view that has already played it once.
            if (show && !shown) {
              view.classList.remove('is-swap');
              void view.offsetWidth;
              view.classList.add('is-swap');
            }
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
      bar.style.transitionDelay = index % 5 * 60 + 'ms';
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
    var update = function update() {
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
        // The masthead is sticky, but an announcement strip may sit above it —
        // so the line appears once the masthead itself has hit the top, not
        // once the page has moved at all.
        head.classList.toggle('is-off', head.getBoundingClientRect().top <= 0 && window.scrollY > 8);
      }
      ticking = false;
    };
    window.addEventListener('scroll', function () {
      if (!ticking) {
        ticking = true;
        window.requestAnimationFrame(update);
      }
    }, {
      passive: true
    });
    window.addEventListener('resize', update, {
      passive: true
    });
    update();
  }

  /* ------------------------------------------------------------------ */

  function rise() {
    var blocks = all('.rise');
    if (!('IntersectionObserver' in window)) {
      blocks.forEach(function (block) {
        block.classList.add('is-in');
      });
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
    }, {
      threshold: .06,
      rootMargin: '0px 0px -8% 0px'
    });
    blocks.forEach(function (block) {
      watcher.observe(block);
    });
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

/***/ }),

/***/ "./resources/assets/styles/docs.css":
/*!******************************************!*\
  !*** ./resources/assets/styles/docs.css ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/assets/styles/site.css":
/*!******************************************!*\
  !*** ./resources/assets/styles/site.css ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_core-js_modules_es_array_for-each_js-node_modules_core-js_modules_es_arr-ef9a92"], () => (__webpack_exec__("./resources/assets/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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b0UsU0FBUyxFQUFFLEdBQUc7TUFBRUMsVUFBVSxFQUFFO0lBQWtCLENBQUMsQ0FBQztJQUVyRFQsTUFBTSxDQUFDeEgsT0FBTyxDQUFDLFVBQVV5SCxLQUFLLEVBQUU7TUFBRUMsT0FBTyxDQUFDUSxPQUFPLENBQUNULEtBQUssQ0FBQztJQUFFLENBQUMsQ0FBQztFQUNoRTtFQUVBLFNBQVN2RCxJQUFJQSxDQUFBLEVBQUc7SUFDWkUsSUFBSSxDQUFDLENBQUM7SUFDTnFCLElBQUksQ0FBQyxDQUFDO0lBQ05PLElBQUksQ0FBQyxDQUFDO0lBQ051QixJQUFJLENBQUMsQ0FBQztFQUNWO0VBRUEsSUFBSWpJLFFBQVEsQ0FBQzZFLFVBQVUsS0FBSyxTQUFTLEVBQUU7SUFDbkM3RSxRQUFRLENBQUN3QixnQkFBZ0IsQ0FBQyxrQkFBa0IsRUFBRW9ELElBQUksQ0FBQztFQUN2RCxDQUFDLE1BQU07SUFDSEEsSUFBSSxDQUFDLENBQUM7RUFDVjtBQUNKLENBQUMsRUFBRSxDQUFDOzs7Ozs7Ozs7Ozs7QUM1Tko7Ozs7Ozs7Ozs7Ozs7QUNBQSIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3Jlc291cmNlcy9hc3NldHMvYXBwLmpzIiwid2VicGFjazovLy8uL3Jlc291cmNlcy9hc3NldHMvZG9jcy5qcyIsIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvYXNzZXRzL3NpdGUuanMiLCJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2Fzc2V0cy9zdHlsZXMvZG9jcy5jc3M/MTE4MyIsIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvYXNzZXRzL3N0eWxlcy9zaXRlLmNzcz8wMDE1Il0sInNvdXJjZXNDb250ZW50IjpbIi8vIFRoZSBsYW5kaW5nIHNoZWV0IGFuZCBpdHMgYmVoYXZpb3VyIGFyZSB0YWtlbiBmcm9tIHRoZSBjb25jZXB0IGFzIHRoZXkgd2VyZVxuLy8gYXV0aG9yZWQuIEV2ZXJ5dGhpbmcgZG9jdW1lbnRhdGlvbi1zcGVjaWZpYyBsaXZlcyBiZXNpZGUgdGhlbSBhbmQgaXMgd3JpdHRlblxuLy8gYWdhaW5zdCB0aGUgc2FtZSB0b2tlbnMgYW5kIHRoZSBzYW1lIHBsYWluLUpTIHN0eWxlLlxuaW1wb3J0IFwiLi9zdHlsZXMvc2l0ZS5jc3NcIjtcbmltcG9ydCBcIi4vc3R5bGVzL2RvY3MuY3NzXCI7XG5cbmltcG9ydCBcIi4vc2l0ZS5qc1wiO1xuaW1wb3J0IFwiLi9kb2NzLmpzXCI7XG4iLCIvKipcbiAqIHBocGxydC5vcmcg4oCUIGRvY3VtZW50YXRpb24gcGFnZXNcbiAqXG4gKiAgIG5hdigpICAgIOKAlCBjb2xsYXBzaWJsZSBjaGFwdGVycywgcmVtZW1iZXJlZCBwZXIgY2F0ZWdvcnkgaW4gbG9jYWxTdG9yYWdlLlxuICogICBmaW5kKCkgICDigJQgdGhlIHNlYXJjaCBmaWVsZCwgdGFsa2luZyB0byAvYXBpL3NlYXJjaC5qc29uLlxuICogICBkcmF3ZXIoKSDigJQgdGhlIHNpZGViYXIgYXMgYSBzaGVldCBvbiBuYXJyb3cgc2NyZWVucy5cbiAqXG4gKiBXcml0dGVuIGluIHRoZSBzYW1lIHBsYWluIHN0eWxlIGFzIHRoZSBsYW5kaW5nIHNoZWV0OiBubyBmcmFtZXdvcmssIGFuZFxuICogZXZlcnkgcGFnZSBzdGlsbCByZWFkYWJsZSB3aXRoIHRoaXMgZmlsZSBtaXNzaW5nIOKAlCB0aGUgY2hhcHRlcnMgcmVuZGVyXG4gKiBvcGVuLCBhbmQgdGhlIHNlYXJjaCBmaWVsZCBzaW1wbHkgZG9lcyBub3RoaW5nLlxuICovXG4oZnVuY3Rpb24gKCkge1xuICAgICd1c2Ugc3RyaWN0JztcblxuICAgIHZhciBNSU5fUVVFUlkgPSAyO1xuICAgIHZhciBERUJPVU5DRSA9IDMwMDtcblxuICAgIGZ1bmN0aW9uIGFsbChzZWxlY3Rvciwgcm9vdCkge1xuICAgICAgICByZXR1cm4gQXJyYXkucHJvdG90eXBlLnNsaWNlLmNhbGwoKHJvb3QgfHwgZG9jdW1lbnQpLnF1ZXJ5U2VsZWN0b3JBbGwoc2VsZWN0b3IpKTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiB0ZXh0KHZhbHVlKSB7XG4gICAgICAgIHZhciBub2RlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG5cbiAgICAgICAgbm9kZS50ZXh0Q29udGVudCA9IHZhbHVlID09IG51bGwgPyAnJyA6IFN0cmluZyh2YWx1ZSk7XG5cbiAgICAgICAgcmV0dXJuIG5vZGUuaW5uZXJIVE1MO1xuICAgIH1cblxuICAgIC8qIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xuXG4gICAgZnVuY3Rpb24gbmF2KCkge1xuICAgICAgICBhbGwoJ1tkYXRhLW5hdi1ncm91cF0nKS5mb3JFYWNoKGZ1bmN0aW9uIChncm91cCkge1xuICAgICAgICAgICAgdmFyIGJ1dHRvbiA9IGdyb3VwLnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLW5hdi10b2dnbGVdJyk7XG4gICAgICAgICAgICB2YXIga2V5ID0gJ21lbnUtc2hvd24tJyArIGdyb3VwLmdldEF0dHJpYnV0ZSgnZGF0YS1uYXYtZ3JvdXAnKTtcblxuICAgICAgICAgICAgaWYgKCFidXR0b24pIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHZhciBhcHBseSA9IGZ1bmN0aW9uIChvcGVuKSB7XG4gICAgICAgICAgICAgICAgZ3JvdXAuY2xhc3NMaXN0LnRvZ2dsZSgnaXMtc2h1dCcsICFvcGVuKTtcbiAgICAgICAgICAgICAgICBidXR0b24uc2V0QXR0cmlidXRlKCdhcmlhLWV4cGFuZGVkJywgb3BlbiA/ICd0cnVlJyA6ICdmYWxzZScpO1xuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgLy8gQW55dGhpbmcgbm90IGV4cGxpY2l0bHkgY2xvc2VkIGJlZm9yZSBpcyBvcGVuLCB3aGljaCBtYXRjaGVzXG4gICAgICAgICAgICAvLyB3aGF0IHRoZSBtYXJrdXAgcmVuZGVycyB3aXRob3V0IHRoaXMgc2NyaXB0LlxuICAgICAgICAgICAgYXBwbHkod2luZG93LmxvY2FsU3RvcmFnZS5nZXRJdGVtKGtleSkgIT09ICcwJyk7XG5cbiAgICAgICAgICAgIGJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgb3BlbiA9IGJ1dHRvbi5nZXRBdHRyaWJ1dGUoJ2FyaWEtZXhwYW5kZWQnKSAhPT0gJ3RydWUnO1xuXG4gICAgICAgICAgICAgICAgd2luZG93LmxvY2FsU3RvcmFnZS5zZXRJdGVtKGtleSwgb3BlbiA/ICcxJyA6ICcwJyk7XG4gICAgICAgICAgICAgICAgYXBwbHkob3Blbik7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgLyogLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXG5cbiAgICBmdW5jdGlvbiBmaW5kKCkge1xuICAgICAgICB2YXIgcm9vdCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLWZpbmRdJyk7XG5cbiAgICAgICAgaWYgKCFyb290KSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICB2YXIgaW5wdXQgPSByb290LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLWZpbmQtaW5wdXRdJyk7XG4gICAgICAgIHZhciBvdXQgPSByb290LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLWZpbmQtb3V0XScpO1xuICAgICAgICB2YXIgdGltZXIgPSBudWxsO1xuICAgICAgICB2YXIgc2VxID0gMDtcblxuICAgICAgICB2YXIgc2hvdyA9IGZ1bmN0aW9uIChodG1sKSB7XG4gICAgICAgICAgICBvdXQuaW5uZXJIVE1MID0gaHRtbDtcbiAgICAgICAgICAgIG91dC5oaWRkZW4gPSBmYWxzZTtcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgaGlkZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIG91dC5oaWRkZW4gPSB0cnVlO1xuICAgICAgICAgICAgb3V0LmlubmVySFRNTCA9ICcnO1xuICAgICAgICB9O1xuXG4gICAgICAgIHZhciByZW5kZXIgPSBmdW5jdGlvbiAoaXRlbXMpIHtcbiAgICAgICAgICAgIHJldHVybiBpdGVtcy5tYXAoZnVuY3Rpb24gKGl0ZW0pIHtcbiAgICAgICAgICAgICAgICB2YXIgdGl0bGUgPSB0ZXh0KGl0ZW0udGl0bGUpO1xuXG4gICAgICAgICAgICAgICAgLy8gVGhlIEFQSSByZXBvcnRzIHdoaWNoIGZyYWdtZW50cyBtYXRjaGVkOyBtYXJrIHRoZW0gdXBcbiAgICAgICAgICAgICAgICAvLyBhZnRlciBlc2NhcGluZywgc28gdGhlIHBheWxvYWQgY2FuIG5ldmVyIGluamVjdCBtYXJrdXAuXG4gICAgICAgICAgICAgICAgKGl0ZW0uZm91bmQgfHwgW10pLmZvckVhY2goZnVuY3Rpb24gKG1hdGNoKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBuZWVkbGUgPSB0ZXh0KG1hdGNoKTtcblxuICAgICAgICAgICAgICAgICAgICBpZiAobmVlZGxlICE9PSAnJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdGl0bGUgPSB0aXRsZS5zcGxpdChuZWVkbGUpLmpvaW4oJzxzcGFuPicgKyBuZWVkbGUgKyAnPC9zcGFuPicpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICByZXR1cm4gJzxhIGNsYXNzPVwiZmluZF9faGl0XCIgaHJlZj1cIicgKyB0ZXh0KGl0ZW0udXJsKSArICdcIj4nICtcbiAgICAgICAgICAgICAgICAgICAgJzxiPicgKyB0aXRsZSArICc8L2I+JyArXG4gICAgICAgICAgICAgICAgICAgICc8aT4nICsgdGV4dChpdGVtLnBhZ2UpICsgJzwvaT4nICtcbiAgICAgICAgICAgICAgICAgICAgJzwvYT4nO1xuICAgICAgICAgICAgfSkuam9pbignJyk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdmFyIHJ1biA9IGZ1bmN0aW9uIChxdWVyeSkge1xuICAgICAgICAgICAgdmFyIG1pbmUgPSArK3NlcTtcblxuICAgICAgICAgICAgd2luZG93LmZldGNoKCcvYXBpL3NlYXJjaC5qc29uJywge1xuICAgICAgICAgICAgICAgIG1ldGhvZDogJ1BPU1QnLFxuICAgICAgICAgICAgICAgIGhlYWRlcnM6IHtcbiAgICAgICAgICAgICAgICAgICAgJ2NvbnRlbnQtdHlwZSc6ICdhcHBsaWNhdGlvbi9qc29uJyxcbiAgICAgICAgICAgICAgICAgICAgJ2FjY2VwdCc6ICdhcHBsaWNhdGlvbi9qc29uJ1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgYm9keTogSlNPTi5zdHJpbmdpZnkoeyBxdWVyeTogcXVlcnkgfSlcbiAgICAgICAgICAgIH0pLnRoZW4oZnVuY3Rpb24gKHJlc3BvbnNlKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHJlc3BvbnNlLmpzb24oKTtcbiAgICAgICAgICAgIH0pLnRoZW4oZnVuY3Rpb24gKHJlc3VsdCkge1xuICAgICAgICAgICAgICAgIC8vIEEgc2xvd2VyIGVhcmxpZXIgcmVxdWVzdCBtdXN0IG5vdCBvdmVyd3JpdGUgYSBuZXdlciBhbnN3ZXJcbiAgICAgICAgICAgICAgICBpZiAobWluZSAhPT0gc2VxKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZiAocmVzdWx0LmVycm9yKSB7XG4gICAgICAgICAgICAgICAgICAgIHRocm93IG5ldyBFcnJvcihyZXN1bHQuZXJyb3IpO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIGlmICghcmVzdWx0LmRhdGEgfHwgcmVzdWx0LmRhdGEubGVuZ3RoID09PSAwKSB7XG4gICAgICAgICAgICAgICAgICAgIHNob3coJzxkaXYgY2xhc3M9XCJmaW5kX19ub3RlXCI+Tm90aGluZyBmb3VuZDwvZGl2PicpO1xuXG4gICAgICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBzaG93KHJlbmRlcihyZXN1bHQuZGF0YSkpO1xuICAgICAgICAgICAgfSkuY2F0Y2goZnVuY3Rpb24gKGVycm9yKSB7XG4gICAgICAgICAgICAgICAgaWYgKG1pbmUgPT09IHNlcSkge1xuICAgICAgICAgICAgICAgICAgICBzaG93KCc8ZGl2IGNsYXNzPVwiZmluZF9fbm90ZSBmaW5kX19ub3RlLS1iYWRcIj5TZWFyY2ggaXMgdW5hdmFpbGFibGU8L2Rpdj4nKTtcbiAgICAgICAgICAgICAgICAgICAgd2luZG93LmNvbnNvbGUgJiYgY29uc29sZS5lcnJvcihlcnJvcik7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH07XG5cbiAgICAgICAgaW5wdXQuYWRkRXZlbnRMaXN0ZW5lcignaW5wdXQnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgcXVlcnkgPSBpbnB1dC52YWx1ZS50cmltKCk7XG5cbiAgICAgICAgICAgIHdpbmRvdy5jbGVhclRpbWVvdXQodGltZXIpO1xuXG4gICAgICAgICAgICBpZiAocXVlcnkubGVuZ3RoIDwgTUlOX1FVRVJZKSB7XG4gICAgICAgICAgICAgICAgc2VxKys7XG4gICAgICAgICAgICAgICAgaGlkZSgpO1xuXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aW1lciA9IHdpbmRvdy5zZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHsgcnVuKHF1ZXJ5KTsgfSwgREVCT1VOQ0UpO1xuICAgICAgICB9KTtcblxuICAgICAgICBpbnB1dC5hZGRFdmVudExpc3RlbmVyKCdrZXlkb3duJywgZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgICAgICAgICBpZiAoZXZlbnQua2V5ID09PSAnRXNjYXBlJykge1xuICAgICAgICAgICAgICAgIGlucHV0LnZhbHVlID0gJyc7XG4gICAgICAgICAgICAgICAgc2VxKys7XG4gICAgICAgICAgICAgICAgaGlkZSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICAgICAgaWYgKCFyb290LmNvbnRhaW5zKGV2ZW50LnRhcmdldCkpIHtcbiAgICAgICAgICAgICAgICBoaWRlKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIC8qIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xuXG4gICAgZnVuY3Rpb24gZHJhd2VyKCkge1xuICAgICAgICB2YXIgYnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignW2RhdGEtZG9jcy10b2dnbGVdJyk7XG4gICAgICAgIHZhciBzaWRlID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2RvY3Mtc2lkZScpO1xuXG4gICAgICAgIGlmICghYnV0dG9uIHx8ICFzaWRlKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICB2YXIgc2V0ID0gZnVuY3Rpb24gKG9wZW4pIHtcbiAgICAgICAgICAgIHNpZGUuY2xhc3NMaXN0LnRvZ2dsZSgnaXMtb3BlbicsIG9wZW4pO1xuICAgICAgICAgICAgYnV0dG9uLnNldEF0dHJpYnV0ZSgnYXJpYS1leHBhbmRlZCcsIG9wZW4gPyAndHJ1ZScgOiAnZmFsc2UnKTtcbiAgICAgICAgfTtcblxuICAgICAgICBidXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBzZXQoYnV0dG9uLmdldEF0dHJpYnV0ZSgnYXJpYS1leHBhbmRlZCcpICE9PSAndHJ1ZScpO1xuICAgICAgICB9KTtcblxuICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICAgICAgaWYgKHNpZGUuY2xhc3NMaXN0LmNvbnRhaW5zKCdpcy1vcGVuJykgJiZcbiAgICAgICAgICAgICAgICAhc2lkZS5jb250YWlucyhldmVudC50YXJnZXQpICYmXG4gICAgICAgICAgICAgICAgIWJ1dHRvbi5jb250YWlucyhldmVudC50YXJnZXQpKSB7XG4gICAgICAgICAgICAgICAgc2V0KGZhbHNlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcigna2V5ZG93bicsIGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICAgICAgaWYgKGV2ZW50LmtleSA9PT0gJ0VzY2FwZScpIHtcbiAgICAgICAgICAgICAgICBzZXQoZmFsc2UpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICAvLyBGb2xsb3dpbmcgYSBsaW5rIGluc2lkZSB0aGUgc2hlZXQgc2hvdWxkIGNsb3NlIGl0XG4gICAgICAgIHNpZGUuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgICAgIGlmIChldmVudC50YXJnZXQuY2xvc2VzdCgnYScpKSB7XG4gICAgICAgICAgICAgICAgc2V0KGZhbHNlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gYm9vdCgpIHtcbiAgICAgICAgbmF2KCk7XG4gICAgICAgIGZpbmQoKTtcbiAgICAgICAgZHJhd2VyKCk7XG4gICAgfVxuXG4gICAgaWYgKGRvY3VtZW50LnJlYWR5U3RhdGUgPT09ICdsb2FkaW5nJykge1xuICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgYm9vdCk7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgYm9vdCgpO1xuICAgIH1cbn0pKCk7XG4iLCIvKipcbiAqIHBocGxydC5vcmcg4oCUIGxhbmRpbmcsIGNvbmNlcHQgIzYgKFwicmUtcmVhZFwiKVxuICpcbiAqICAgdGFicygpICAgIOKAlCBbZGF0YS10YWJzPVwiaWRcIl0gKyBbZGF0YS12aWV3LWtleV0gc3dpdGNoIFtkYXRhLXZpZXddIGJsb2Nrcy5cbiAqICAgICAgICAgICAgICAgVHdvIGxvb2tzIHNoYXJlIGl0OiB0aGUgcGlsbHMgYWJvdmUgdGhlIGdyYW1tYXJzIGFuZCB0aGVcbiAqICAgICAgICAgICAgICAgc3RhZ2UgbGlzdCBpbiB0aGUgZGlhZ25vc3RpY3MgYmFuZC4gVGhlIGluY29taW5nIGJsb2NrIGlzXG4gKiAgICAgICAgICAgICAgIG1hcmtlZCAuaXMtc3dhcCBmb3IgdGhlIGxlbmd0aCBvZiBpdHMgZW50cmFuY2UuXG4gKiAgIGNvcHkoKSAgICDigJQgdGhlIGluc3RhbGwgY29tbWFuZC5cbiAqICAgZmlsbHMoKSAgIOKAlCBtZXRlciB3aWR0aHMgZnJvbSBkYXRhLWZpbGwsIHNvIHJlYWwgcGhwYmVuY2ggbnVtYmVycyBjYW4gYmVcbiAqICAgICAgICAgICAgICAgZHJvcHBlZCBpbiBhcyBwbGFpbiBhdHRyaWJ1dGVzIGxhdGVyLlxuICogICByYWlsKCkgICAg4oCUIG1hcmtzIHRoZSBjdXJyZW50IGNoYXB0ZXIsIGFuZCBpbnZlcnRzIHRoZSByYWlsIHdoaWxlIGl0IGlzXG4gKiAgICAgICAgICAgICAgIHNpdHRpbmcgb3ZlciBhIGRhcmsgYmFuZCAoW2RhdGEtbmlnaHRdKS5cbiAqICAgcmlzZSgpICAgIOKAlCBvbmUgcmV2ZWFsLiBFdmVyeXRoaW5nIGVsc2UgaW4gc2VjdGlvbiAxOSBvZiB0aGUgc2hlZXQgaGFuZ3NcbiAqICAgICAgICAgICAgICAgb2ZmIHRoZSAuaXMtaW4gaXQgcHV0cyBvbiBhIGJsb2NrOiB0aGUgc2VxdWVuY2UgaXRzIGNoaWxkcmVuXG4gKiAgICAgICAgICAgICAgIGFycml2ZSBpbiwgdGhlIGRyYXdpbmcgb2YgYSByYWlscm9hZCBkaWFncmFtIGFuZCBvZiB0aGUgZml2ZVxuICogICAgICAgICAgICAgICBiZW5jaG1hcmsgc2VyaWVzLCBhbmQgdGhlIG1ldGVycyBiZWxvdyB0aGVtLlxuICpcbiAqIFRoZSBwYWdlIGlzIGNvbXBsZXRlIHdpdGhvdXQgdGhpcyBmaWxlOiBjb2RlIGlzIGhpZ2hsaWdodGVkIGluIHRoZSBtYXJrdXAsXG4gKiBpbmFjdGl2ZSBwYW5lbHMgY2FycnkgdGhlIGBoaWRkZW5gIGF0dHJpYnV0ZSB0aGVtc2VsdmVzLCB0aGUgY2hhcnRzIGFyZVxuICogcGxvdHRlZCBpbiB0aGUgU1ZHLCBhbmQgZXZlcnkgbWV0ZXIgaGFzIGl0cyBudW1iZXIgd3JpdHRlbiBuZXh0IHRvIGl0LiBUaGVcbiAqIHJldmVhbHMgaGlkZSBub3RoaW5nIHVudGlsIHRoZSBpbmxpbmUgc2NyaXB0IGluIDxoZWFkPiBoYXMgcHV0IGBqc2Agb24gdGhlXG4gKiByb290LCBzbyBhIHBhZ2UgdGhhdCBuZXZlciBydW5zIGFueSBvZiB0aGlzIGlzIHNpbXBseSBhbHJlYWR5IHRoZXJlLlxuICovXG4oZnVuY3Rpb24gKCkge1xuICAgICd1c2Ugc3RyaWN0JztcblxuICAgIGZ1bmN0aW9uIGFsbChzZWxlY3Rvciwgcm9vdCkge1xuICAgICAgICByZXR1cm4gQXJyYXkucHJvdG90eXBlLnNsaWNlLmNhbGwoKHJvb3QgfHwgZG9jdW1lbnQpLnF1ZXJ5U2VsZWN0b3JBbGwoc2VsZWN0b3IpKTtcbiAgICB9XG5cbiAgICAvKiAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cblxuICAgIGZ1bmN0aW9uIHRhYnMoKSB7XG4gICAgICAgIGFsbCgnW2RhdGEtdGFic10nKS5mb3JFYWNoKGZ1bmN0aW9uIChiYXIpIHtcbiAgICAgICAgICAgIHZhciBzY29wZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGJhci5nZXRBdHRyaWJ1dGUoJ2RhdGEtdGFicycpKSB8fCBiYXI7XG4gICAgICAgICAgICB2YXIgYnV0dG9ucyA9IGFsbCgnW2RhdGEtdmlldy1rZXldJywgYmFyKTtcblxuICAgICAgICAgICAgYnV0dG9ucy5mb3JFYWNoKGZ1bmN0aW9uIChidXR0b24pIHtcbiAgICAgICAgICAgICAgICBidXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciB3YW50ID0gYnV0dG9uLmdldEF0dHJpYnV0ZSgnZGF0YS12aWV3LWtleScpO1xuXG4gICAgICAgICAgICAgICAgICAgIGJ1dHRvbnMuZm9yRWFjaChmdW5jdGlvbiAob3RoZXIpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBvbiA9IG90aGVyID09PSBidXR0b247XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIG90aGVyLmNsYXNzTGlzdC50b2dnbGUoJ2lzLW9uJywgb24pO1xuICAgICAgICAgICAgICAgICAgICAgICAgb3RoZXIuc2V0QXR0cmlidXRlKCdhcmlhLXNlbGVjdGVkJywgb24gPyAndHJ1ZScgOiAnZmFsc2UnKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICAgICAgYWxsKCdbZGF0YS12aWV3XScsIHNjb3BlKS5mb3JFYWNoKGZ1bmN0aW9uICh2aWV3KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgc2hvdyA9IHZpZXcuZ2V0QXR0cmlidXRlKCdkYXRhLXZpZXcnKSA9PT0gd2FudDtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBzaG93biA9ICF2aWV3LmhpZGRlbjtcblxuICAgICAgICAgICAgICAgICAgICAgICAgdmlldy5oaWRkZW4gPSAhc2hvdztcblxuICAgICAgICAgICAgICAgICAgICAgICAgLy8gQSB2aWV3IGFycml2aW5nIGJ5IGNsaWNrIGdldHMgdGhlIHNhbWUgc2hvcnQgZW50cmFuY2VcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vIHRoZSBzY3JvbGwgZ2l2ZXMgYSBibG9jaywgYW5kIGl0cyByYWlscm9hZCBkaWFncmFtIGlzXG4gICAgICAgICAgICAgICAgICAgICAgICAvLyBkcmF3biBhZ2FpbiDigJQgdGhlIHBhbmVsIGFyb3VuZCBpdCB3YXMgcmV2ZWFsZWQgbG9uZ1xuICAgICAgICAgICAgICAgICAgICAgICAgLy8gYWdvLCBzbyBub3RoaW5nIGVsc2Ugd291bGQgbW92ZS4gRHJvcHBpbmcgdGhlIGNsYXNzXG4gICAgICAgICAgICAgICAgICAgICAgICAvLyBhbmQgcmVhZGluZyBhIGxheW91dCBwcm9wZXJ0eSByZXN0YXJ0cyB0aGUgYW5pbWF0aW9uXG4gICAgICAgICAgICAgICAgICAgICAgICAvLyBvbiBhIHZpZXcgdGhhdCBoYXMgYWxyZWFkeSBwbGF5ZWQgaXQgb25jZS5cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChzaG93ICYmICFzaG93bikge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZpZXcuY2xhc3NMaXN0LnJlbW92ZSgnaXMtc3dhcCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZvaWQgdmlldy5vZmZzZXRXaWR0aDtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2aWV3LmNsYXNzTGlzdC5hZGQoJ2lzLXN3YXAnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICAgICAgZmlsbHMoc2NvcGUpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIC8qIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xuXG4gICAgZnVuY3Rpb24gZmlsbHMocm9vdCkge1xuICAgICAgICBhbGwoJ1tkYXRhLWZpbGxdJywgcm9vdCB8fCBkb2N1bWVudCkuZm9yRWFjaChmdW5jdGlvbiAoYmFyLCBpbmRleCkge1xuICAgICAgICAgICAgdmFyIHZhbHVlID0gcGFyc2VGbG9hdChiYXIuZ2V0QXR0cmlidXRlKCdkYXRhLWZpbGwnKSkgfHwgMDtcblxuICAgICAgICAgICAgYmFyLnN0eWxlLnRyYW5zaXRpb25EZWxheSA9IChpbmRleCAlIDUpICogNjAgKyAnbXMnO1xuICAgICAgICAgICAgYmFyLnN0eWxlLndpZHRoID0gTWF0aC5tYXgoMywgTWF0aC5taW4oMTAwLCB2YWx1ZSkpICsgJyUnO1xuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICAvKiAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cblxuICAgIGZ1bmN0aW9uIGNvcHkoKSB7XG4gICAgICAgIGFsbCgnW2RhdGEtY29weV0nKS5mb3JFYWNoKGZ1bmN0aW9uIChidXR0b24pIHtcbiAgICAgICAgICAgIGJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgc291cmNlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihidXR0b24uZ2V0QXR0cmlidXRlKCdkYXRhLWNvcHknKSk7XG4gICAgICAgICAgICAgICAgdmFyIGxhYmVsID0gYnV0dG9uLnF1ZXJ5U2VsZWN0b3IoJ3NwYW4nKTtcblxuICAgICAgICAgICAgICAgIGlmICghc291cmNlIHx8ICFuYXZpZ2F0b3IuY2xpcGJvYXJkKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBuYXZpZ2F0b3IuY2xpcGJvYXJkLndyaXRlVGV4dChzb3VyY2UudGV4dENvbnRlbnQudHJpbSgpKS50aGVuKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIHdhcyA9IGxhYmVsID8gbGFiZWwudGV4dENvbnRlbnQgOiAnJztcblxuICAgICAgICAgICAgICAgICAgICBidXR0b24uY2xhc3NMaXN0LmFkZCgnaXMtZG9uZScpO1xuXG4gICAgICAgICAgICAgICAgICAgIGlmIChsYWJlbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbGFiZWwudGV4dENvbnRlbnQgPSAnY29waWVkJztcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIHdpbmRvdy5zZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGJ1dHRvbi5jbGFzc0xpc3QucmVtb3ZlKCdpcy1kb25lJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChsYWJlbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxhYmVsLnRleHRDb250ZW50ID0gd2FzO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9LCAxNTAwKTtcbiAgICAgICAgICAgICAgICB9LCBmdW5jdGlvbiAoKSB7fSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgLyogLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXG5cbiAgICBmdW5jdGlvbiByYWlsKCkge1xuICAgICAgICB2YXIgbmF2ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnJhaWwnKTtcbiAgICAgICAgdmFyIGxpbmtzID0gYWxsKCcucmFpbCBhJyk7XG4gICAgICAgIHZhciBoZWFkID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnRvcCcpO1xuXG4gICAgICAgIGlmICghbGlua3MubGVuZ3RoKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICB2YXIgc2VjdGlvbnMgPSBsaW5rcy5tYXAoZnVuY3Rpb24gKGxpbmspIHtcbiAgICAgICAgICAgIHJldHVybiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChsaW5rLmdldEF0dHJpYnV0ZSgnZGF0YS1zcHknKSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHZhciBuaWdodHMgPSBhbGwoJ1tkYXRhLW5pZ2h0XScpO1xuICAgICAgICB2YXIgdGlja2luZyA9IGZhbHNlO1xuXG4gICAgICAgIHZhciB1cGRhdGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgbGluZSA9IHdpbmRvdy5zY3JvbGxZICsgd2luZG93LmlubmVySGVpZ2h0ICogLjM4O1xuICAgICAgICAgICAgdmFyIGN1cnJlbnQgPSAwO1xuXG4gICAgICAgICAgICBzZWN0aW9ucy5mb3JFYWNoKGZ1bmN0aW9uIChzZWN0aW9uLCBpbmRleCkge1xuICAgICAgICAgICAgICAgIGlmIChzZWN0aW9uICYmIHNlY3Rpb24ub2Zmc2V0VG9wIDw9IGxpbmUpIHtcbiAgICAgICAgICAgICAgICAgICAgY3VycmVudCA9IGluZGV4O1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBsaW5rcy5mb3JFYWNoKGZ1bmN0aW9uIChsaW5rLCBpbmRleCkge1xuICAgICAgICAgICAgICAgIGxpbmsuY2xhc3NMaXN0LnRvZ2dsZSgnaXMtaGVyZScsIGluZGV4ID09PSBjdXJyZW50KTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAvLyBUaGUgcmFpbCBpcyBmaXhlZCBhdCB0aGUgbWlkZGxlIG9mIHRoZSBzY3JlZW4g4oCUIGludmVydCBpdCB3aGlsZVxuICAgICAgICAgICAgLy8gdGhhdCBtaWRkbGUgaXMgaW5zaWRlIGEgZGFyayBiYW5kXG4gICAgICAgICAgICB2YXIgbWlkZGxlID0gd2luZG93LnNjcm9sbFkgKyB3aW5kb3cuaW5uZXJIZWlnaHQgLyAyO1xuICAgICAgICAgICAgdmFyIG9uTmlnaHQgPSBuaWdodHMuc29tZShmdW5jdGlvbiAoYmFuZCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBtaWRkbGUgPiBiYW5kLm9mZnNldFRvcCAmJiBtaWRkbGUgPCBiYW5kLm9mZnNldFRvcCArIGJhbmQub2Zmc2V0SGVpZ2h0O1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIGlmIChuYXYpIHtcbiAgICAgICAgICAgICAgICBuYXYuY2xhc3NMaXN0LnRvZ2dsZSgnb24tbmlnaHQnLCBvbk5pZ2h0KTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKGhlYWQpIHtcbiAgICAgICAgICAgICAgICAvLyBUaGUgbWFzdGhlYWQgaXMgc3RpY2t5LCBidXQgYW4gYW5ub3VuY2VtZW50IHN0cmlwIG1heSBzaXQgYWJvdmUgaXQg4oCUXG4gICAgICAgICAgICAgICAgLy8gc28gdGhlIGxpbmUgYXBwZWFycyBvbmNlIHRoZSBtYXN0aGVhZCBpdHNlbGYgaGFzIGhpdCB0aGUgdG9wLCBub3RcbiAgICAgICAgICAgICAgICAvLyBvbmNlIHRoZSBwYWdlIGhhcyBtb3ZlZCBhdCBhbGwuXG4gICAgICAgICAgICAgICAgaGVhZC5jbGFzc0xpc3QudG9nZ2xlKCdpcy1vZmYnLCBoZWFkLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLnRvcCA8PSAwICYmIHdpbmRvdy5zY3JvbGxZID4gOCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHRpY2tpbmcgPSBmYWxzZTtcbiAgICAgICAgfTtcblxuICAgICAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignc2Nyb2xsJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgaWYgKCF0aWNraW5nKSB7XG4gICAgICAgICAgICAgICAgdGlja2luZyA9IHRydWU7XG4gICAgICAgICAgICAgICAgd2luZG93LnJlcXVlc3RBbmltYXRpb25GcmFtZSh1cGRhdGUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LCB7IHBhc3NpdmU6IHRydWUgfSk7XG5cbiAgICAgICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3Jlc2l6ZScsIHVwZGF0ZSwgeyBwYXNzaXZlOiB0cnVlIH0pO1xuICAgICAgICB1cGRhdGUoKTtcbiAgICB9XG5cbiAgICAvKiAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cblxuICAgIGZ1bmN0aW9uIHJpc2UoKSB7XG4gICAgICAgIHZhciBibG9ja3MgPSBhbGwoJy5yaXNlJyk7XG5cbiAgICAgICAgaWYgKCEoJ0ludGVyc2VjdGlvbk9ic2VydmVyJyBpbiB3aW5kb3cpKSB7XG4gICAgICAgICAgICBibG9ja3MuZm9yRWFjaChmdW5jdGlvbiAoYmxvY2spIHsgYmxvY2suY2xhc3NMaXN0LmFkZCgnaXMtaW4nKTsgfSk7XG4gICAgICAgICAgICBmaWxscygpO1xuXG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICB2YXIgd2F0Y2hlciA9IG5ldyBJbnRlcnNlY3Rpb25PYnNlcnZlcihmdW5jdGlvbiAoZW50cmllcykge1xuICAgICAgICAgICAgZW50cmllcy5mb3JFYWNoKGZ1bmN0aW9uIChlbnRyeSkge1xuICAgICAgICAgICAgICAgIGlmICghZW50cnkuaXNJbnRlcnNlY3RpbmcpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIGVudHJ5LnRhcmdldC5jbGFzc0xpc3QuYWRkKCdpcy1pbicpO1xuICAgICAgICAgICAgICAgIGZpbGxzKGVudHJ5LnRhcmdldCk7XG4gICAgICAgICAgICAgICAgd2F0Y2hlci51bm9ic2VydmUoZW50cnkudGFyZ2V0KTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9LCB7IHRocmVzaG9sZDogLjA2LCByb290TWFyZ2luOiAnMHB4IDBweCAtOCUgMHB4JyB9KTtcblxuICAgICAgICBibG9ja3MuZm9yRWFjaChmdW5jdGlvbiAoYmxvY2spIHsgd2F0Y2hlci5vYnNlcnZlKGJsb2NrKTsgfSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gYm9vdCgpIHtcbiAgICAgICAgdGFicygpO1xuICAgICAgICBjb3B5KCk7XG4gICAgICAgIHJhaWwoKTtcbiAgICAgICAgcmlzZSgpO1xuICAgIH1cblxuICAgIGlmIChkb2N1bWVudC5yZWFkeVN0YXRlID09PSAnbG9hZGluZycpIHtcbiAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIGJvb3QpO1xuICAgIH0gZWxzZSB7XG4gICAgICAgIGJvb3QoKTtcbiAgICB9XG59KSgpO1xuIiwiLy8gZXh0cmFjdGVkIGJ5IG1pbmktY3NzLWV4dHJhY3QtcGx1Z2luXG5leHBvcnQge307IiwiLy8gZXh0cmFjdGVkIGJ5IG1pbmktY3NzLWV4dHJhY3QtcGx1Z2luXG5leHBvcnQge307Il0sIm5hbWVzIjpbIk1JTl9RVUVSWSIsIkRFQk9VTkNFIiwiYWxsIiwic2VsZWN0b3IiLCJyb290IiwiQXJyYXkiLCJwcm90b3R5cGUiLCJzbGljZSIsImNhbGwiLCJkb2N1bWVudCIsInF1ZXJ5U2VsZWN0b3JBbGwiLCJ0ZXh0IiwidmFsdWUiLCJub2RlIiwiY3JlYXRlRWxlbWVudCIsInRleHRDb250ZW50IiwiU3RyaW5nIiwiaW5uZXJIVE1MIiwibmF2IiwiZm9yRWFjaCIsImdyb3VwIiwiYnV0dG9uIiwicXVlcnlTZWxlY3RvciIsImtleSIsImdldEF0dHJpYnV0ZSIsImFwcGx5Iiwib3BlbiIsImNsYXNzTGlzdCIsInRvZ2dsZSIsInNldEF0dHJpYnV0ZSIsIndpbmRvdyIsImxvY2FsU3RvcmFnZSIsImdldEl0ZW0iLCJhZGRFdmVudExpc3RlbmVyIiwic2V0SXRlbSIsImZpbmQiLCJpbnB1dCIsIm91dCIsInRpbWVyIiwic2VxIiwic2hvdyIsImh0bWwiLCJoaWRkZW4iLCJoaWRlIiwicmVuZGVyIiwiaXRlbXMiLCJtYXAiLCJpdGVtIiwidGl0bGUiLCJmb3VuZCIsIm1hdGNoIiwibmVlZGxlIiwic3BsaXQiLCJqb2luIiwidXJsIiwicGFnZSIsInJ1biIsInF1ZXJ5IiwibWluZSIsImZldGNoIiwibWV0aG9kIiwiaGVhZGVycyIsImJvZHkiLCJKU09OIiwic3RyaW5naWZ5IiwidGhlbiIsInJlc3BvbnNlIiwianNvbiIsInJlc3VsdCIsImVycm9yIiwiRXJyb3IiLCJkYXRhIiwibGVuZ3RoIiwiY29uc29sZSIsInRyaW0iLCJjbGVhclRpbWVvdXQiLCJzZXRUaW1lb3V0IiwiZXZlbnQiLCJjb250YWlucyIsInRhcmdldCIsImRyYXdlciIsInNpZGUiLCJnZXRFbGVtZW50QnlJZCIsInNldCIsImNsb3Nlc3QiLCJib290IiwicmVhZHlTdGF0ZSIsInRhYnMiLCJiYXIiLCJzY29wZSIsImJ1dHRvbnMiLCJ3YW50Iiwib3RoZXIiLCJvbiIsInZpZXciLCJzaG93biIsInJlbW92ZSIsIm9mZnNldFdpZHRoIiwiYWRkIiwiZmlsbHMiLCJpbmRleCIsInBhcnNlRmxvYXQiLCJzdHlsZSIsInRyYW5zaXRpb25EZWxheSIsIndpZHRoIiwiTWF0aCIsIm1heCIsIm1pbiIsImNvcHkiLCJzb3VyY2UiLCJsYWJlbCIsIm5hdmlnYXRvciIsImNsaXBib2FyZCIsIndyaXRlVGV4dCIsIndhcyIsInJhaWwiLCJsaW5rcyIsImhlYWQiLCJzZWN0aW9ucyIsImxpbmsiLCJuaWdodHMiLCJ0aWNraW5nIiwidXBkYXRlIiwibGluZSIsInNjcm9sbFkiLCJpbm5lckhlaWdodCIsImN1cnJlbnQiLCJzZWN0aW9uIiwib2Zmc2V0VG9wIiwibWlkZGxlIiwib25OaWdodCIsInNvbWUiLCJiYW5kIiwib2Zmc2V0SGVpZ2h0IiwiZ2V0Qm91bmRpbmdDbGllbnRSZWN0IiwidG9wIiwicmVxdWVzdEFuaW1hdGlvbkZyYW1lIiwicGFzc2l2ZSIsInJpc2UiLCJibG9ja3MiLCJibG9jayIsIndhdGNoZXIiLCJJbnRlcnNlY3Rpb25PYnNlcnZlciIsImVudHJpZXMiLCJlbnRyeSIsImlzSW50ZXJzZWN0aW5nIiwidW5vYnNlcnZlIiwidGhyZXNob2xkIiwicm9vdE1hcmdpbiIsIm9ic2VydmUiXSwic291cmNlUm9vdCI6IiJ9