
import ko from "knockout";

export default class MenuViewModel {
    /**
     * @type {KnockoutObservable<string>}
     */
    query = ko.observable('')
        .extend({ throttle: 300 });

    /**
     * @type {KnockoutObservable<boolean>}
     */
    shown = ko.observable(false)
        .extend({ throttle: 300 });

    /**
     * @type {KnockoutObservableArray<Object>}
     */
    results = ko.observableArray();

    /**
     * @type {[KnockoutObservable<boolean>]}
     */
    #menu = [];

    constructor() {
        this.query.subscribe(async value => {
            if (value.length < 2) {
                this.results([]);

                return;
            }

            try {
                let response = await fetch('/api/search.json', {
                    method: 'POST',
                    headers: {
                        'content-type': 'application/json',
                        'accept': 'application/json',
                    },
                    body: JSON.stringify({
                        query: value,
                    })
                });

                let result = await response.json();

                for (let entry of result.data) {
                    for (let query of entry.found) {
                        entry.title = entry.title
                            .replace(query, `<span>${query}</span>`);
                    }
                }

                this.results(result.data);
            } catch (e) {
                this.results([]);
                console.error(e);
            }
        });
    }

    focus() {
        this.shown(true);
    }

    blur() {
        this.shown(false);
    }

    /**
     * @param {number} id
     * @returns {KnockoutObservable<boolean>}
     */
    menu(id) {
        if (! this.#menu[id]) {
            let defaultValue = localStorage.getItem(`menu-shown-${id}`);

            defaultValue = defaultValue === null || defaultValue === '1';

            this.#menu[id] = ko.observable(!!defaultValue);
        }

        return this.#menu[id];
    }

    /**
     * @param {number} id
     * @returns {boolean}
     */
    toggle(id) {
        let status = this.menu(id);

        localStorage.setItem(`menu-shown-${id}`, status() ? '0' : '1');

        status(! status());

        return false;
    }
}
