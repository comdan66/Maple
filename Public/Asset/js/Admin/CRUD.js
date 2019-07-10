// OAdropUploadImg-20180115
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a(jQuery)}(function(a){a.fn.extend({OAdropUploadImg:function(b){var c={},d=function(b){e(b),a(this).attr("data-loading","讀取中..").removeClass("no")},e=function(b){a(this).removeAttr("data-loading").addClass("no"),b.attr("src","")},f=function(a,b,c){var d=new Image;d.src=a.target.result,d.onload=function(){_vmxw=1024;var a=document.createElement("canvas");6==c||8==c?(a.height=d.width,a.width=d.height):(a.width=d.width,a.height=d.height),Math.max(a.width,a.height)>_vmxw&&(a.width>a.height?(a.height=_vmxw/a.width*a.height,a.width=_vmxw):(a.width=_vmxw/a.height*a.width,a.height=_vmxw)),3==c?(a.getContext("2d").transform(-1,0,0,-1,a.width,a.height),a.getContext("2d").drawImage(d,0,0,a.width,a.height)):6==c?(a.getContext("2d").transform(0,1,-1,0,a.width,0),a.getContext("2d").drawImage(d,0,0,a.height,a.width)):8==c?(a.getContext("2d").transform(0,-1,1,0,0,a.height),a.getContext("2d").drawImage(d,0,0,a.height,a.width)):a.getContext("2d").drawImage(d,0,0,a.width,a.height),b(a)}},g=function(a,b){var c=new FileReader;c.onload=function(a){var c=new DataView(a.target.result);if(65496!=c.getUint16(0,!1))return f(this,b,-2);for(var d=c.byteLength,e=2;e<d;){var g=c.getUint16(e,!1);if(e+=2,65505==g){if(1165519206!=c.getUint32(e+=2,!1))return f(this,b,-1);var h=18761==c.getUint16(e+=6,!1);e+=c.getUint32(e+4,h);var i=c.getUint16(e,h);e+=2;for(var j=0;j<i;j++)if(274==c.getUint16(e+12*j,h))return f(this,b,c.getUint16(e+12*j+8,h))}else{if(65280!=(65280&g))break;e+=c.getUint16(e,!1)}}return f(this,b,-1)}.bind(this),c.readAsArrayBuffer(a.slice(0,65536))},h=function(b,c){var d=a(this),e=new FileReader;e.onload=function(a){g.bind(a,c,function(a){b.attr("src",a.toDataURL()).load(function(){d.removeAttr("data-loading")})})()},e.readAsDataURL(c)},i=function(b){var c=a(this),f=c.find("img"),g=c.find('input[type="file"]').change(function(){d.bind(c,f)(),a(this).val().length&&a(this).get(0).files&&a(this).get(0).files[0]?h.bind(c,f,a(this).get(0).files[0])():e.bind(c,f)(),a(this).css({top:0,left:0})});f.attr("src").length||c.addClass("no"),c.bind("dragover",function(b){b.stopPropagation(),b.preventDefault(),a(this).addClass("ho"),g.offset({top:b.originalEvent.pageY-15,left:b.originalEvent.pageX-10})}).bind("dragleave",function(b){b.stopPropagation(),b.preventDefault(),a(this).removeClass("ho")}).bind("drop",function(b){a(this).removeClass("ho")})};return a(this).each(function(){i.bind(a(this))(a.extend(!0,c,b))}),a(this)}})});

$(function() {
  var $body = $('body');

  function reflashError(msg) {
    window.notify.add({
      type: 'bug',
      title: '操作發生了錯誤！',
      message: '發生不明錯誤，點擊查看問題！',
      action: function() {
        window.ajaxError.show('發生不明錯誤，為了確保資料正確性，請重新整理頁面然後回報給工程師。概略原因：' + msg);
      }
    });
  }
  function updateCounter(key, result) {
    if (typeof key === 'undefined') return;
    if (typeof this.$el === 'undefined') this.$el = $('*[data-cntlabel*="' + key + '"][data-cnt]');
    this.$el.each(function() {
      $(this).attr('data-cnt', (result ? -1 : 1) + parseInt($(this).attr('data-cnt'), 10));
    });
  }

  $('form.search .conditions-btn').click(function() {
    $(this).parent().toggleClass('show');
  });

  if (typeof $.fn.datetimepicker !== 'undefined') {
    jQuery.datetimepicker.setLocale('zh-TW');
    $('input[type="datetime"]').datetimepicker({ format: 'Y-m-d H:i:00', formatTime:'H:i:00', step: 10 });
    $('input[type="date"]').datetimepicker({ timepicker:false, format:'Y-m-d' });
  }

  $('.drop-img').OAdropUploadImg();
  function mutiImg($obj) {
    
    if ($obj.length <= 0) return;

    $obj.on('click', '.drop-img > a', function() {
      var $parent = $(this).parent();
      $parent.remove();
    });

    $obj.on('change', '.drop-img > input[type="file"]', function() {
      if (!$(this).val().length) return;

      var $parent = $(this).parent();
      $parent.find('input[type="hidden"]').remove();

      if ($obj.find('>.drop-img').last().hasClass('no')) return;
      var $n = $parent.clone().removeAttr('data-loading').addClass('no');
      $n.find('img').attr('src', '');
      $n.find('input').val('');
      $n.OAdropUploadImg().insertAfter($parent);
    });
  }
  mutiImg($('.multi-drop-imgs'));

  $('form.form').submit(function() {
    var $that = $(this);
    if ($that.data('submited')) return false;
    else $that.data('submited', true);
    $(this).find('input[type="checkbox"][data-off]').each(function() {
      if ($(this).prop('checked') !== false) return ;
      $that.prepend($('<input />').attr('type', 'hidden').attr('name', $(this).attr('name')).val($(this).data('off')));
    });
    window.loading.show('請稍候..', 300);
    return true;
  });


  if (typeof $.fn.sortable === 'function') {
    !function(t){"function"==typeof define&&define.amd?define(["jquery","jquery-ui"],t):t(jQuery)}(function(t){var i,n={},o=function(t){var i,n=document.createElement("div");for(i=0;i<t.length;i++)if(void 0!=n.style[t[i]])return t[i];return""};n.transform=o(["transform","WebkitTransform","MozTransform","OTransform","msTransform"]),n.transition=o(["transition","WebkitTransition","MozTransition","OTransition","msTransition"]),i=n.transform&&n.transition,t.widget("ui.sortable",t.ui.sortable,{options:{animation:0},_rearrange:function(o,r){var s,a,e={},m={},f=t.trim(this.options.axis);if(!parseInt(this.currentContainer.options.animation)||!f)return this._superApply(arguments);s=t(r.item[0]),a=("up"==this.direction?"":"-")+s["x"==f?"width":"height"]()+"px",this._superApply(arguments),i?e[n.transform]=("x"==f?"translateX":"translateY")+"("+a+")":(e={position:"relative"})["x"==f?"left":"top"]=a,s.css(e),i?(e[n.transition]=n.transform+" "+this.options.animation+"ms",e[n.transform]="",m[n.transform]="",m[n.transition]="",setTimeout(function(){s.css(e)},0)):(m.top="",m.position="",s.animate({top:"",position:""},this.options.animation)),setTimeout(function(){s.css(m)},this.options.animation)}})});
    $('table.list.dragable[data-sorturl]').each(function() {
      var $that = $(this);
      var ori = [];

      $that.sortable({
        items: $that.find('tr[data-sort][data-id]'),
        revert: true,
        handle: $that.find('span.drag'),
        animation: 300,
        placeholder: 'placeholder',
        connectWith: $that.find('tbody'),

        start: function(e, ui){
          ui.placeholder.height(ui.item.height());
          ori = $that.find('tr[data-sort][data-id]:visible').map(function(i) {
            return {
              id: $(this).data('id'),
              sort: $(this).data('sort')
            };
          }).toArray();
        },
        helper: function(e, $tr) {
          var $originals = $tr.children();
          $tr.children().each(function(index) {
            $(this).width($originals.eq(index).outerWidth());
          });
          return $tr;
        },
        update: function(e, ui) {
          var now = $that.find('tr[data-sort][data-id]:visible').map(function(i) {
            return {
              id: $(this).data('id'),
              sort: $(this).data('sort')
            };
          }).toArray();

          if (ori.length != now.length)
            window.notification.add({icon: 'icon-38', color: 'rgba(234, 84, 75, 1.00)', title: '設定錯誤！', message: '※ 不明原因錯誤，請重新整理網頁確認。請點擊此訊息顯示詳細錯誤。'}, null, function() {
              window.ajaxError.show(
                  'ori: ' + JSON.stringify(ori) +
                  'now: ' + JSON.stringify(now)
                );
            });

          var chg = [];
          for (var i = 0; i < ori.length; i++)
            if (ori[i].sort != now[i].sort)
              chg.push({
                'id': now[i].id,
                'ori': now[i].sort,
                'now': ori[i].sort
              });

          $.ajax({
            url: $that.data('sorturl'),
            data: { changes: chg },
            async: true, cache: false, dataType: 'json', type: 'POST'
          })
          .done(function(result) {
            result.forEach(function(t) {
              $that.find('tr[data-id="' + t.id + '"]').data('sort', t.sort);
            });
          })
          .fail(function(result) {
            var tmp = isJsonString(result.responseText);
            window.notify.add({
              type: 'bug',
              title: 'Ajax 錯誤，點擊查看問題！',
              message: tmp === null ? result.responseText : JSON.stringify(result),
              action: window.ajaxError.show
            });
          });
        }
      });
    });
  }

  if (typeof $.fn.ckeditor !== 'undefined') {
    var filebrowserImageUploadUrl = $('#api-ckeditor-image-upload').val();
    var filebrowserImageBrowseUrl = $('#api-ckeditor-image-browse').val();

    $('textarea.ckeditor').ckeditor({
      filebrowserImageUploadUrl: filebrowserImageUploadUrl,
      filebrowserImageBrowseUrl: filebrowserImageBrowseUrl,
      droplerConfig: { backend: 'basic', settings: { uploadUrl: filebrowserImageUploadUrl } },
      skin: 'oa',
      height: 300,
      resize_enabled: false,
      removePlugins: 'elementspath',
      toolbarGroups: [{ name: '1', groups: [ 'mode', 'tools', 'links', 'basicstyles', 'colors', 'insert', 'list', 'Table' ] }],
      removeButtons: 'Strike,Underline,Italic,HorizontalRule,Smiley,Subscript,Superscript,Forms,Save,NewPage,Print,Preview,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace,SelectAll,Scayt,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Form,RemoveFormat,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Flash,PageBreak,Iframe,About,Styles',
      extraPlugins: 'tableresize,dropler',
      // contentsCss: 'assets/css/ckeditor_contents.css'
    });
  }

  $('.switch.ajax[data-column][data-url][data-true][data-false]').each (function () {
    var $that = $(this),
        column = $that.data ('column'),
        url = $that.data ('url'),
        vtrue = $that.data ('true'),
        vfalse = $that.data ('false'),
        $input = $that.find ('input[type="checkbox"]');

    $input.click(function() {
      if ($that.hasClass('loading')) return;
      else $that.addClass('loading');

      var data = {};
      data[column] = $(this).prop('checked') ? vtrue : vfalse;

      $.ajax({
        url: url,
        data: data,
        async: true, cache: false, dataType: 'json', type: 'POST'
      })
      .done(function(result) {
        if (typeof result[column] === 'undefined')
          return reflashError('列表開關器 Ajax Response 200，但是格式錯誤！');

        $input.prop('checked', result[column] == vtrue);
        $that.removeClass('loading');

        if (result[column] == data[column])
          updateCounter($that.data('cntlabel'), result[column] == vtrue);
      })
      .fail(function(result) {
        $input.prop('checked', data[column] != vtrue);
        $that.removeClass('loading');

        var tmp = isJsonString(result.responseText);
        window.notify.add({
          type: 'bug',
          title: 'Ajax 錯誤，點擊查看問題！',
          message: tmp === null ? result.responseText : JSON.stringify(result),
          action: window.ajaxError.show
        });
      });
    });
  });

  window.choiceBox = {
    $el: null,
    storageKey: 'maple.choice.box',
    min: function(key, bo) {
      var k = 'min.' + window.choiceBox.storageKey + '.' + key;
      return typeof bo === 'undefined' ? window.storage.get(k) : window.storage.set(k, bo);
    },
    get: function(key) {
      var objs = window.storage.get(window.choiceBox.storageKey + '.' + key);
      return objs ? objs : [];
    },
    set: function(key, objs) {
      window.storage.set(window.choiceBox.storageKey + '.' + key, objs);
    },
    has: function(key, id) {
      var setStorage = window.choiceBox.get(key);
      setStorage = setStorage.filter(function(u) { return u.id == id; });
      return setStorage.length ? true : false;
    },
    add: function(key, obj) {
      var setStorage = window.choiceBox.get(key);
      for (var k in setStorage) if (obj.id == setStorage[k].id) return;
      setStorage.push(obj);
      window.choiceBox.set(key, setStorage);
    },
    del: function(key, obj) {
      var setStorage = window.choiceBox.get(key);
      setStorage = setStorage.filter(function(u) { return u.id != obj; });
      setStorage = $.unique(setStorage);
      window.choiceBox.set(key, setStorage);
    },
    init: function($body) {
      var selector = 'table.list .checkbox > input[type="checkbox"][data-feature="choicebox"][data-name][data-id][data-method][data-action][data-type]';
      var types = $(selector).map(function() { return $(this).data('type'); }).toArray().filter(function(value, index, self) { return self.indexOf(value) === index; }).map(function(type) { return { type: type, method: $(selector + '[data-type="' + type + '"]').first().data('method'), action: $(selector + '[data-type="' + type + '"]').first().data('action'), }; });

      if (!types.length) return ;

      this.$el = $('<div />').attr('id', 'choice-box').appendTo($body);

      types.forEach(function(type) {
        var $el = null;
        var $header = $('<header />').text(type.type).click(function() { window.choiceBox.min(type.type, $el.toggleClass('min').hasClass('min')); });
        var $items = $('<div />').addClass('items');
        var $footer = $('<footer />').append($('<a />').text('全部取消').click(function() {$items.find('.item').find('a').click(); })).append($('<button />').attr('type', 'submit').text('確定送出'));

        $el = $('<form />').addClass('choice-box').attr('action', type.action).attr('method', type.method).addClass(window.choiceBox.min(type.type) ? 'min' : null).append($header).append($items).append($footer).appendTo(window.choiceBox.$el).submit(function() { var $that = $(this); if (!confirm('確定送出？')) return false; if ($that.data('submited')) return false; else $that.data('submited', true); return true; });

        var cnt = function() { $el.attr('data-cnt', $items.find('.item').length); $header.attr('data-cnt', $items.find('.item').length); };
        var rItem = function(obj) { return $('<div />').addClass('item').attr('data-id', obj.id).append($('<span />').text(obj.name)).append($('<a />').click(function() { $(this).closest('.item').remove(); cnt(); window.choiceBox.del(type.type, obj.id); var $t = $(selector + '[data-type="' + type.type + '"][data-id="' + obj.id + '"]'); if (!$t.length) return false; $t.prop('checked', false); })); };

        $items.append(window.choiceBox.get(type.type).map(rItem));
        cnt();
        
        $(selector + '[data-type="' + type.type + '"]').click(function() {
          if ($(this).prop('checked')) { window.choiceBox.add(type.type, {id: $(this).data('id'), name: $(this).data('name')}); $items.append(rItem({id: $(this).data('id'), name: $(this).data('name')})); }
          else { window.choiceBox.del(type.type, $(this).data('id')); $items.find('.item[data-id="' + $(this).data('id') + '"]').remove(); }
          cnt();
        }).map(function() { if (window.choiceBox.has(type.type, $(this).data('id'))) $(this).prop('checked', true); });
      });
    }
  };
  window.choiceBox.init($body);

  window.oaips = {
    ni: 0, $objs: {}, $pswp: null, $conter: null, callPvfunc : null,
    init: function($b, c) { this.$pswp = $('<div class="pswp"><div class="pswp__bg"></div><div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="關閉(Esc)"></button><button class="pswp__button pswp__button--link" title="鏈結"></button><button class="pswp__button pswp__button--fs" title="全螢幕切換"></button><button class="pswp__button pswp__button--zoom" title="放大/縮小"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div><button class="pswp__button pswp__button--arrow--left" title="上一張"></button><button class="pswp__button pswp__button--arrow--right" title="下一張"></button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div></div>').appendTo($b); this.$conter = this.$pswp.find('div.pswp__caption__center'); if (c && typeof c === 'function') this.callPvfunc = c; return this; },
    show: function(index, $obj, da, fromURL) {
      if (isNaN(index) || !window.oaips.$pswp || !window.oaips.$conter) return;

      var items = $obj.get(0).$objs.map(function() {
        var $img = $(this).find('img'), $figcaption = $(this).find('figcaption'), $himg = $(this).find('img.h');
        var $i = $himg.length ? $himg : $img;

        return {
          w: $i.get(0).width,
          h: $i.get(0).height,
          src: $i.attr('src'),
          href: $i.attr('src'),
          title: $img.attr('alt') && $img.attr('alt').length ? $img.attr('alt') : $figcaption.html(),
          content: $img.attr('alt') && $img.attr('alt').length ? $figcaption.html() : '',
          el: $(this).get(0)
        };
      }).toArray();

      var options = {
        showHideOpacity: true,
        galleryUID: $obj.data('pswp-uid'),
        showAnimationDuration: da ? 0 : 500,
        index: parseInt(index, 10) - (fromURL ? 1 : 0),
        getThumbBoundsFn: function(index) {
          var pageYScroll = window.pageYOffset || document.documentElement.scrollTop, rect = items[index].el.getBoundingClientRect();
          return { x:rect.left, y:rect.top + pageYScroll, w:rect.width };
        }
      };

      var g = new PhotoSwipe(window.oaips.$pswp.get(0), PhotoSwipeUI_Default, items, options, $obj.get(0).$objs.map(function() {
        return $(this).data('pvid') ? $(this).data('pvid') : '';// $(this).data('id');
      }));

      g.init(function(pvid) { if (!(window.oaips.callPvfunc && (typeof window.oaips.callPvfunc === 'function') && pvid.length &&( pvid.split('-').length == 2))) return false; window.oaips.callPvfunc(pvid.split('-')[0], pvid.split('-')[1]) });

      window.oaips.$conter.width(Math.floor(g.currItem.w * g.currItem.fitRatio) - 20);
      g.listen('beforeChange', function() { window.oaips.$conter.removeClass('show'); window.oaips.$conter.width(Math.floor(g.currItem.w * g.currItem.fitRatio - 20)); });
      g.listen('afterChange', function() { window.oaips.$conter.addClass('show'); });
      g.listen('resize', function() { window.oaips.$conter.width(Math.floor(g.currItem.w * g.currItem.fitRatio - 20)); });

      return this;
    },
    set: function(gs, fnx) {
      var $obj = (gs instanceof jQuery) ? gs : $(gs);
      if (!$obj.length) return false;

      $obj.each(function(i) {
        var $that = $(this);

        $that.data('pswp-uid', window.oaips.ni + i + 1);
        $that.get(0).$objs = $that.find(fnx).each(function() { if($(this).data('ori')) $(this).append($('<img />').attr('src', $(this).data('ori')).addClass('h')); });
        $that.find(fnx).click(function() { window.oaips.show($that.get(0).$objs.index($(this)), $that); });

        window.oaips.$objs[window.oaips.ni + i] = $that;
      });

      window.oaips.ni = window.oaips.ni + 1;

      return this;
    },
    listenUrl: function() {
      var params = {};
      window.location.hash.replace('#', '').split('&').forEach(function(t, i) { if (!(t && (t = t.split('=')).length && t[1].length)) return; params[t[0]] = t[1]; });
      if (!window.oaips.$objs[params.gid - 1] || Object.keys(params).length === 0 || typeof params.gid === 'undefined' || typeof params.pid === 'undefined') return false;
      setTimeout(function() { window.oaips.show(params.pid - 1, window.oaips.$objs[params.gid - 1], true, true); }, 500);
      return this;
    }
  };
  window.oaips.init($body);

  $('.oaips').each(function() {
    var $oaips = $('<div />').addClass('oaips');
    var $oaip = $(this).find('img').map(function() {
      var $that = $(this);
      var $div = $('<div />').addClass('oaip');
      if ($that.attr('data-pvid') !== undefined) $div.attr('data-pvid', $that.attr('data-pvid'));
      if ($that.attr('data-ori') !== undefined) $div.attr('data-ori', $that.attr('data-ori'));
      $div.css({ 'background-image': 'url(' + $that.attr('src') + ')' });
      return $div.append($('<img />').attr('src', $that.attr('src'))).prependTo($oaips);
    });
    if (!$oaip.length)
      return;

    $oaips.attr('data-cnt', $oaip.length).appendTo($(this));
    window.oaips.set($oaips, '.oaip');
  });

  $('.panel.show .medias .image').each(function() {
    var $that = $(this);
    var $img = $that.find('img');
    if (!$img.length)
      return;

    $that.css({ 'background-image': 'url(' + $img.attr('src') + ')' });
  });

  window.oaips.set('.medias', '.image');
  window.oaips.listenUrl();
});