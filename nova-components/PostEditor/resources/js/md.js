import MarkdownIt from 'markdown-it';
var hljs = require('highlight.js');

var md = new MarkdownIt({
    html: false,
    xhtmlOut: true,
    breaks: true,
    langPrefix: 'language-',
    linkify: true,
    typographer: false,
    quotes: '“”‘’',

    highlight: function (str, lang) {
        if (lang && hljs.getLanguage(lang)) {
            try {
                return '<pre class="hljs"><code>' +
                    hljs.highlight(lang, str, true).value +
                    '</code></pre>';
            } catch (__) { }
        }

        return '<pre class="hljs"><code>' + md.utils.escapeHtml(str) + '</code></pre>';
    }
});

md.use(require('markdown-it-sub'));

md.use(require('markdown-it-sup'));

md.use(require('markdown-it-footnote'));

md.use(require('markdown-it-container'), 'note', {

    validate: function (params) {
        return true;
    },

    render: function (tokens, idx) {
        if (tokens[idx].nesting === 1) {
            return '<div class="note-base note-' + tokens[idx].info.trim() + '">' + '\n';

        } else {
            return '</div>\n';
        }
    }
});

// md.use(function (md) {
//     md.core.ruler.push('anchor', state => {
//         const tokens = state.tokens
//         tokens.filter(token => {
//             return token.type === 'heading_open' &&
//                 (token.tag === 'h2' || token.tag === 'h3')
//         }).forEach((token, index) => {
//             const title = tokens[tokens.indexOf(token) + 1]
//                 .children
//                 .filter(token => token.type === 'text' || token.type === 'code_inline')
//                 .reduce((accumulator, currentToken) => accumulator + currentToken.content.trim(), '')

//             const id = 'toc' + (title + index).hashCode()

//             token.attrSet('id', id)
//             token.attrSet('data-title', title)
//             token.attrSet('data-id', id)
//             token.attrSet('class', 'anchor')
//         });
//     })
// });

export {
    md
}
