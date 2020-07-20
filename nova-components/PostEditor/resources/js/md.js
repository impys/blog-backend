import MarkdownIt from 'markdown-it';
let hljs = require('highlight.js');
let MarkdownItContainer = require('markdown-it-container');

let md = new MarkdownIt({
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
        return `<pre class="hljs" lang="${lang}"><code>${hljs.highlight(lang, str, true).value}</code></pre>`
      } catch (__) { }
    }

    return `<pre class="hljs"><code>${md.utils.escapeHtml(str)}</code></pre>`;
  }
});

md.use(require('markdown-it-sub'));

md.use(require('markdown-it-sup'));

md.use(require('markdown-it-footnote'));

// 自定义 note 容器，用于创建提醒消息
md.use(MarkdownItContainer, 'note', {

  validate: function (param) {
    return ['tip', 'warning', 'danger'].some(item => item === param.trim());
  },

  render: function (tokens, idx) {
    if (tokens[idx].nesting === 1) {// 标签头
      return `<div class="note-base note-${tokens[idx].info.trim()}">\n`;

    } else {// 标签尾
      return '</div>\n';
    }
  }
});

// 自定义 page 容器，用于内联跳转
md.use(MarkdownItContainer, 'page', {

  validate: function (param) {
    return param.trim().match(/^page\s+(.*)$/);
  },

  render: function (tokens, idx) {
    let m = tokens[idx].info.trim().match(/^page\s+(.*)$/);

    if (tokens[idx].nesting === 1) {
      let route = m[1].split(" ").reduce((carry, currentValue) => { return carry + '/' + currentValue }, '');

      return `<div class="page" route=${route} data-url="atom.ac.cn${route}">` + '\n';

    } else {
      return '</div>\n';
    }
  }
});

// 自定义 music 容器，用于内联跳转
md.use(MarkdownItContainer, 'music', {

  validate: function (param) {
    return param.trim().match(/^music\s+(.*)$/);
  },

  render: function (tokens, idx) {
    let m = tokens[idx].info.trim().match(/^music\s+(.*)$/);

    if (tokens[idx].nesting === 1) {
      return `<div class="music" status="stop" src=${m[1]}>` + '\n';
    } else {
      return '</div>\n';
    }
  }
});

// 解析 h2 h3 标签，用于创建目录
md.use(function (md) {
  md.core.ruler.push('anchor', state => {
    const tokens = state.tokens
    tokens.filter(token => {
      return token.type === 'heading_open' &&
        (token.tag === 'h2' || token.tag === 'h3')
    }).forEach((token, index) => {
      const title = tokens[tokens.indexOf(token) + 1]
        .children
        .filter(token => token.type === 'text' || token.type === 'code_inline')
        .reduce((accumulator, currentToken) => accumulator + currentToken.content.trim(), '')

      const id = 'toc' + (title + index).hashCode()

      token.attrSet('id', id)
      token.attrSet('data-title', title)
      token.attrSet('data-id', id)
      token.attrSet('class', 'anchor')
    });
  })
});

// append _blank to a element
md.use(require('markdown-it-for-inline'), 'url_new_win', 'link_open', function (tokens, idx) {
  let aIndex = tokens[idx].attrIndex('target');
  if (aIndex < 0) {
    tokens[idx].attrPush(['target', '_blank']);
  } else {
    tokens[idx].attrs[aIndex][1] = '_blank';
  }
});

export {
  md
}

