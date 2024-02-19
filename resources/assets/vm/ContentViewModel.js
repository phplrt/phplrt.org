
import { getHighlighter } from 'shiki';

const DEFAULT_THEME = 'one-dark-pro';

const PP2_LANGUAGE = {
    "displayName": "pp2",
    "fileTypes": [ "pp2" ],
    "name": "pp2",
    "patterns": [
        {
            "begin": "/\\*\\*(?=\\s)",
            "end": "\\*/",
            "name": "comment.block"
        },
        {
            "begin": "(^\\s+)?(?=//)",
            "end": "(?!\\G)",
            "patterns": [
                {
                    "begin": "//",
                    "end": "\\n|(?=\\?>)",
                    "name": "comment.line"
                }
            ]
        },
        {
            "begin": "^\\h*%(token|skip|pragma)",
            "end": "\\n",
            "name": "keyword.operator",
            "patterns": [
                {
                    "begin": "T_\\w+|root",
                    "end": "\\b",
                    "name": "keyword.other"
                },
                {
                    "match": "[^\\s]+",
                    "name": "storage.modifier"
                },
            ]
        },
        {
            "match": "\\w+\\h*\\(\\)",
            "name": "storage.type"
        },
        {
            "match": "::\\w+::",
            "name": "entity.name.function.php"
        },
        {
            "match": "<\\w+>",
            "name": "keyword.other"
        },
        {
            "match": "\\+|\\?|\\||\\(|\\)|\\*|:|;",
            "name": "punctuation"
        },
    ],
    "scopeName": "text.pp2"
};

export default class ContentViewModel {
    constructor(ctx) {
        for (let {lang, block} of this.#getCodeBlocks(ctx)) {
            block.style.filter = 'blur(1px)';
        }

        this.#loadHighlighter(ctx);
    }

    async #loadHighlighter(ctx) {
        const highlighter = await getHighlighter({
            langs: [ 'php', 'json', 'bash', PP2_LANGUAGE ],
            themes: [DEFAULT_THEME]
        })

        for (let {lang, block} of this.#getCodeBlocks(ctx)) {
            block.parentNode.innerHTML = await highlighter.codeToHtml(block.innerText, {
                lang: lang,
                theme: DEFAULT_THEME
            });
        }
    }

    #load(promise, block) {
        promise.then(function (result) {
            block.parentNode.innerHTML = result;
        });
    }

    *#getCodeBlocks(ctx) {
        const blocks = ctx.querySelectorAll('pre > code');

        blocks: for (const code of blocks) {
            for (const className of code.classList) {
                if (className.startsWith('language-')) {
                    yield {
                        lang: className.substring(9),
                        block: code,
                    };

                    continue blocks;
                }
            }
        }
    }
}
