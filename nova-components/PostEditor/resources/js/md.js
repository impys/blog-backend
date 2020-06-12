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

// 自定义 note 容器，用于创建提醒消息
md.use(require('markdown-it-container'), 'note', {

  validate: function (param) {
    return ['tip', 'warning', 'danger'].some(item => item === param.trim());
  },

  render: function (tokens, idx) {
    if (tokens[idx].nesting === 1) {
      return '<div class="note-base note-' + tokens[idx].info.trim() + '">' + '\n';

    } else {
      return '</div>\n';
    }
  }
});

// 自定义 page 容器，用于内联跳转
md.use(require('markdown-it-container'), 'page', {

  validate: function (param) {
    return param.trim().match(/^page\s+(.*)$/);
  },

  render: function (tokens, idx) {
    var m = tokens[idx].info.trim().match(/^page\s+(.*)$/);

    if (tokens[idx].nesting === 1) {
      let route = m[1].split(" ").reduce((carry, currentValue) => { return carry + '/' + currentValue }, '');

      return `<div class="page" route=${route}>` + '\n';

    } else {
      return '</div>\n';
    }
  }
});

// append _blank to a element
md.use(require('markdown-it-for-inline'), 'url_new_win', 'link_open', function (tokens, idx) {
  var aIndex = tokens[idx].attrIndex('target');
  if (aIndex < 0) {
    tokens[idx].attrPush(['target', '_blank']);
  } else {
    tokens[idx].attrs[aIndex][1] = '_blank';
  }
});

export {
  md
}

