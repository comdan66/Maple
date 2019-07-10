/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 - 2019, Ginkgo
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

function isJsonString(str) { try { return JSON.parse(str); } catch (e) { return null; } }

window.storage = {
  exist: function() { return typeof Storage !== 'undefined' && typeof JSON !== 'undefined'; },
  set: function(key, val) {
    if (!this.exist())
      return false;
    try {
      localStorage.setItem(key, val === undefined ? null : JSON.stringify(val));
      return true;
    } catch(error) {
      return false;
    }
  },
  get: function(key) {
    if (!this.exist())
      return false;
    val = localStorage.getItem(key);
    return JSON.parse(val);
  },
};

window.timer = {
  keys: {},
  delay: function(time, closure) {
    setTimeout(closure, time)
    return this;
  },
  only: function(key, time, closure) {
    if (!this.has(key))
      this.keys[key] = setTimeout(closure, time);
    return this;
  },
  replace: function(key, time, closure) {
    this.clean(key);
    this.keys[key] = setTimeout(closure, time);
    return this;
  },
  clean: function(key) {
    if (!this.has(key)) return true;
    clearTimeout(this.keys[key]);
    this.keys[key] = null;
    delete this.keys[key];
    return this;
  },
  cleanAll: function() {
    for (key in this.keys)
      this.clean(key);
    return this;
  },
  has: function(key) {
    return typeof this.keys[key] !== 'undefined';
  },
};

$(function() {
  var $body = $('body').addClass(window.storage.get('min'));

  $('figure[data-bgurl]').each(function() {
    var $that = $(this);
    var url = $that.data('bgurl');
    $that.css({'background-image': 'url(' + (url.length ? url : '') + ')'});
  });

  window.timer.replace('body.ani', 500, function() {
    $body.addClass('ani');
  });

  $('#menu-container div').prev().click(function() {
    $(this).toggleClass('active');
  });

  $('#hamburger').click(function() {
    $body.toggleClass('min');
    window.storage.set('min', $body.hasClass('min') ? 'min' : null);
  });

  $('a[data-method="delete"]').click (function () { return !confirm ('確定要刪除？') ? false : true; });

  $('#theme').change(function() {
    var url = $('#api-change-theme').val();
    if (!(url && url.length))
      return window.notify.add({ type: 'bug', title: '後台錯誤', message: '沒有設定 API URL.' });
    
    $.ajax ({
      url: url,
      data: { theme: $(this).val() },
      async: true, cache: false, dataType: 'json', type: 'POST'
    })
    .done (function (result) {
      location.reload(true);
    })
    .fail (function (result) {
      var tmp = isJsonString(result.responseText);
      window.notify.add({
        type: 'bug',
        title: 'Ajax 錯誤，點擊查看問題！',
        message: tmp === null ? result.responseText : JSON.stringify(tmp),
        action: window.ajaxError.show
      });
    });
  });

  window.loading = {
    $el: null,
    init: function() {
      if (window.loading.$el) return this;
      window.loading.$el = $('<div />').attr('id', 'loading');
      $body.append(window.loading.$el).append($('<div/>').addClass('popbox-cover'));
      return this;
    },
    show: function(str, timer) {
      if (typeof timer === 'number') {
        window.timer.replace('loading.delay', timer, function() { window.loading.show(str); });
        return this;
      }
      
      window.loading.init();
      window.loading.$el.text(typeof str !== 'undefined' ? str : '').addClass('show');
      window.timer.replace('loading', 100, function() { window.loading.$el.addClass('ani'); });
      return this;
    },
    close: function(closure) {
      window.loading.$el.removeClass('ani');
      window.timer.replace('loading', 333, function() { typeof closure === 'function' && closure(); window.loading.$el.removeClass('show'); });
      return this;
    }
  };

  window.notify = {
    $el: null,
    init: function() {
      if (window.notify.$el) return this;
      window.notify.$el = $('<div />').attr('id', 'notify');
      $body.append(window.notify.$el);
      return this;
    },
    add: function(obj, closure) {
      window.notify.init();

      var $notify = $('<div />');
      var $close = $('<a />').click(function(e) {
        e.stopPropagation();
        typeof closure === 'function' && closure();
        var $parent = $(this).parent().removeClass('show');
        window.timer.delay(300, function() { $parent.remove(); })
      });

      var $icon = null;
      if (typeof obj === 'string') obj = {message: obj};
      if (typeof obj.type !== 'undefined') $icon = $('<figure />').addClass(obj.type);
      if (typeof obj.img !== 'undefined') $icon = $('<figure />').addClass('img').css({'background-image': 'url(' + obj.img + ')'});
      $notify.append($icon)
             .append(typeof obj.title != 'undefined' ? $('<b />').text(obj.title) : null)
             .append($('<span />').text(obj.message))
             .append($close)
             .addClass(typeof obj.action == 'function' ? 'pointer' : null)
             .click(function() {  typeof obj.action == 'function' && $close.click() && obj.action(obj); });

      window.notify.$el.append($notify);
      window.timer.delay(100, function() { $notify.addClass('show'); });
      window.timer.delay(1000 * 10, function() { $close.click(); });
      return this;
    }
  };

  window.ajaxError = {
    $el: null,
    url: $('#api-ajax-error').val(),
    init: function() {
      if (window.ajaxError.$el) return this;
      window.ajaxError.$el = $('<div />').attr('id', 'ajax-error').append($('<label />').text('好的，我知道了！').click(function() { window.ajaxError.close(); }));
      $body.append(window.ajaxError.$el).append($('<div/>').addClass('popbox-cover'));
      return this;
    },
    show: function(str) {
      if (typeof str.message !== 'undefined') str = str.message;
      if (!str.length) return this;
      window.ajaxError.init();
      window.ajaxError.$el.append($('<b />').text('請將下列訊息複製給工程單位')).append($('<div />').text(str)).addClass('show');
      window.timer.replace('ajaxError', 100, function() { window.ajaxError.$el.addClass('ani'); });
      
      if (!window.ajaxError.url) return this;
      $.ajax ({
        url: window.ajaxError.url,
        data: { content: str },
        async: true, cache: false, dataType: 'json', type: 'POST'
      });
      return this;
    },
    close: function(closure) { window.ajaxError.$el.removeClass('show'); }
  };

  // $.ajax ({
  //   url: '/',
  //   data: { content: 'str' },
  //   async: true, cache: false, dataType: 'json', type: 'POST'
  // }).fail(function(result) {
  //   var tmp = isJsonString(result.responseText);
  //   window.notify.add({
  //     type: 'bug',
  //     title: 'Ajax 錯誤，點擊查看問題！',
  //     message: tmp === null ? result.responseText : JSON.stringify(t),
  //     action: window.ajaxError.show
  //   });
  // });


});