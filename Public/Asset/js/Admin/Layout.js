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

  window.timer.replace('body.ani', 200, function() {
    $body.addClass('ani');
  });

  $('.menu-title').click(function() {
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

    Ajax.post({ url: url, data: { theme: $(this).val() } }, function() {
      location.reload(true);
    })
  });

  window.loading = {
    $el: null,
    init: function() {
      if (window.loading.$el) return this;
      window.loading.$el = $('<div />').attr('id', 'loading');
      $body.append(window.loading.$el).append($('<div/>').addClass('-uox-c'));
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

      var $notify = $('<div />').addClass('notify');

      var $close = $('<a />').addClass('notify-close').click(function(e) {
        e.stopPropagation();
        typeof closure === 'function' && closure();
        var $parent = $(this).parent().removeClass('show');
        window.timer.delay(300, function() { $parent.remove(); })
      });

      var $icon = null;
      if (typeof obj === 'string') obj = {message: obj};
      if (typeof obj.type !== 'undefined') $icon = $('<figure />').addClass('notify-img').addClass(obj.type);
      if (typeof obj.img !== 'undefined') $icon = $('<figure />').addClass('notify-img').css({'background-image': 'url(' + obj.img + ')'});
      
      $notify.append($icon)
             .append(typeof obj.title != 'undefined' ? $('<b />').addClass('notify-title').text(obj.title) : null)
             .append($('<span />').addClass('notify-content').text(obj.message))
             .append($close)
             .addClass(typeof obj.action == 'function' ? 'pointer' : null)
             .click(function() {  typeof obj.action == 'function' && $close.click() && obj.action(obj); });

      window.notify.$el.append($notify);
      window.timer.delay(100, function() { $notify.addClass('show'); });
      // window.timer.delay(1000 * 10, function() { $close.click(); });
      return this;
    }
  };

  window.Ajax = {
    $el: null,
    errorApi: $('#api-ajax-error').val(),
    init: function() {
      if (window.Ajax.$el) return this;
      window.Ajax.$el = $('<div />').attr('id', 'ajax-error').append($('<label />').text('好的，我知道了！').click(function() { window.Ajax.close(); }));
      $body.append(window.Ajax.$el).append($('<div/>').addClass('-uox-c'));
      return this;
    },
    show: function(str) {
      if (typeof str.message !== 'undefined') str = str.message;
      if (!str.length) return this;
      window.Ajax.init();
      window.Ajax.$el.append($('<div />').text(str)).addClass('show');
      window.timer.replace('ajaxError', 100, function() { window.Ajax.$el.addClass('ani'); });
      return this;
    },
    close: function(closure) {
      window.Ajax.$el.removeClass('show');
    },
    fail: function(result) {
      var tmp = '';
      if (typeof result['responseText'] === 'undefined') {
        tmp = isJsonString(result);
        tmp = tmp === null ? result : JSON.stringify(tmp);
      } else {
        tmp = isJsonString(result.responseText);
        tmp = '回傳結果：' + (tmp === null ? result.responseText : JSON.stringify(tmp));
      }
      
      if (!Ajax.errorApi) return window.notify.add({
        type: 'bug',
        title: '發生不明錯誤，請重新整理頁面！',
        message: '發生不明錯誤，為了確保資料正確性，請重新整理頁面然後回報給工程師。',
        action: function() {
          window.Ajax.show(tmp);
        }
      });

      Ajax.post({ url: Ajax.errorApi, data: { content: JSON.stringify(result) } }, function(result) {
        return window.notify.add({
          type: 'bug',
          title: '發生不明錯誤，請重新整理頁面！',
          message: '發生不明錯誤，為了確保資料正確性，請重新整理頁面然後回報給工程師。',
          action: function() {
            window.Ajax.show('錯誤 ID：' + result.id + "，" + tmp);
          }
        });
      }, function() {
        return window.notify.add({
          type: 'bug',
          title: '發生不明錯誤，請重新整理頁面！',
          message: '發生不明錯誤，為了確保資料正確性，請重新整理頁面然後回報給工程師。',
          action: function() {
            window.Ajax.show(tmp);
          }
        });
      });
    }, 
    post: function(option, done, fail, complete) {
      option = $.extend({async: true, cache: false, dataType: 'json', type: 'POST'}, option);
      
      $.ajax(option)
       .done(done)
       .fail(typeof fail === 'undefined' ? Ajax.fail : fail)
       .complete(complete);
    },
    get: function(option, done, fail, complete) {
      option = $.extend({async: true, cache: false, dataType: 'json', type: 'GET'}, option);
      
      $.ajax(option)
       .done(done)
       .fail(typeof fail === 'undefined' ? Ajax.fail : fail)
       .complete(complete);
    }
  };
});