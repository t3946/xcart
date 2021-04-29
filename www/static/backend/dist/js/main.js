"use strict";

var _jquery = require("jquery");

var _jquery2 = _interopRequireDefault(_jquery);

require("jquery-ui-dist/jquery-ui");

require("select2");

require("jquery-form");

require("@/js/ajaxTooltip");

require("@/js/CCDashboard");

require("@/js/flash");

require("@/js/form");

require("@/js/formUtils");

require("@/js/jquery.mindy.modal");

require("@/js/list");

require("@/js/main");

require("@/js/mFieldset");

require("@/js/mNotify");

require("@/js/mTooltip");

require("@/js/prevention");

require("@/js/tablePosition");

require("@/js/translates");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

(function () {
  "use strict";

  window.$ = _jquery2.default;

  (0, _jquery2.default)(document).ready(function () {
    var f = (0, _jquery2.default)("fieldset.collapsible");
    if (f.length) {
      f.mfieldset();
    }

    var _loop = function _loop(form) {
      var $form = (0, _jquery2.default)(form);
      if ($form.attr("method") && $form.attr("method").toString().toLowerCase() != "post") {
        var action = (0, _jquery2.default)(form).attr("action");

        if (action.indexOf("?") > -1) {
          action = action.substr(action.indexOf("?") + 1);
          action = action.split("&");

          action.map(function (p) {
            var vars = p.split("=");
            var el = document.createElement("input");
            el.type = "hidden";
            el.name = vars[0];

            if (vars.length > 1) {
              el.value = decodeURI(vars[1]);
            }

            form.prepend(el);
          });
        }
      }
    };

    for (var _iterator = (0, _jquery2.default)("form"), _isArray = Array.isArray(_iterator), _i = 0, _iterator = _isArray ? _iterator : _iterator[Symbol.iterator]();;) {
      var _ref;

      if (_isArray) {
        if (_i >= _iterator.length) break;
        _ref = _iterator[_i++];
      } else {
        _i = _iterator.next();
        if (_i.done) break;
        _ref = _i.value;
      }

      var form = _ref;

      _loop(form);
    }

    if ((0, _jquery2.default)(".admin #o_date").length) {
      (0, _jquery2.default)(".admin form .date_templates > span").on("click", function () {
        var $this = (0, _jquery2.default)(this);
        var $input = (0, _jquery2.default)(".admin #o_date");
        var date_value = "";
        var delimiter = " - ";
        var locale = "en-US";
        var date = new Date();
        var for_datepicker = [date, date];

        switch ($this.data("range")) {
          case "this_month":
            {
              var date2 = new Date(date.getFullYear(), date.getMonth() + 1, 0);
              date.setDate(1);
              // date_value = date.toLocaleDateString(locale) + delimiter + date2.toLocaleDateString(locale);
              date_value = "first day of this month";
              for_datepicker = [date, date2];
              break;
            }
          case "this_week":
            {
              var first = date.getDate() - date.getDay(); // First day is the day of the month - the day of the week
              var last = first + 6; // last day is the first day + 6
              var date1 = new Date(date.setDate(first));
              var _date = new Date(date.setDate(last));
              // date_value = date1.toLocaleDateString(locale) + delimiter + date2.toLocaleDateString(locale);
              date_value = "first day of this week";
              for_datepicker = [date1, _date];
              break;
            }
          case "last_31":
            {
              var _date2 = new Date();
              _date2.setDate(date.getDate() - 31);
              // date_value = date2.toLocaleDateString(locale) + delimiter + date.toLocaleDateString(locale);
              date_value = "-31 day";
              for_datepicker = [_date2, date];
              break;
            }
          case "last_7":
            {
              var _date3 = new Date();
              _date3.setDate(date.getDate() - 7);
              // date_value = date2.toLocaleDateString(locale) + delimiter + date.toLocaleDateString(locale);
              date_value = "-7 day";
              for_datepicker = [_date3, date];
              break;
            }
          case "clear":
            {
              for_datepicker = [];
              break;
            }
          default:
            {
              // date_value = date.toLocaleDateString(locale);
              date_value = "now";
              for_datepicker = [date, date];
            }
        }
        if (typeof $input.airdate === "function") {
          if (for_datepicker.length === 2) {
            $input.airdate().data("airdate").selectDate(for_datepicker);
          } else {
            $input.airdate().data("airdate").clear();
          }
        }

        $input.val(date_value);
      });
    }

    (0, _jquery2.default)("a.mmodal").on("click", function (e) {
      (0, _jquery2.default)(this).mmodal();
      e.preventDefault();
    });

    /*$('.tabs .tabs-title a').on('click', function (e) {
            e.preventDefault();
              $('.tabs .tabs-title a').removeClass('active');
            $('.tabs .tabs-content .tab').removeClass('active');
              let id = $(this).addClass('active').attr('href');
            $(id).addClass('active');
        });*/

    (0, _jquery2.default)(".main-block ").on("change", ".viewer", function () {
      var view = this.value;
      (0, _jquery2.default)(".dashboard-item .filter_owner").each(function () {
        switch (view) {
          case "0":
            (0, _jquery2.default)(this).addClass("hide");
            break;
          case "1":
            (0, _jquery2.default)(this).removeClass("hide");
            break;
          case "2":
            (0, _jquery2.default)(this).addClass("hide");
            break;
        }
      });

      if ((0, _jquery2.default)(this[this.selectedIndex]).attr("data-loc")) {
        document.location = (0, _jquery2.default)(this[this.selectedIndex]).attr("data-loc");
      }
    });

    var $form_bb = (0, _jquery2.default)(".smarty-admin-block .buttons-block:not(.fixed)");
    if ($form_bb.length) {
      var $form = $form_bb.closest("form");

      if ($form.length && $form.innerHeight() + $form.offset()["top"] > (0, _jquery2.default)(window).height()) {
        $form_bb.addClass("fixed");
      }
    }
  });
})();
(function ($) {

    "use strict";

    /**
     * Описание объекта
     */
    var fileapi = function () {
        return fileapi.init.apply(this, arguments);
    };

    /**
     * Расширение объекта
     */
    $.extend(fileapi, {
        /**
         * Настройки по умолчанию
         */
        options: {
            field: null,
            option: 500,
            startPath: undefined,
            listUrl: undefined,
            uploadUrl: undefined,
            filemanSelector: '.file-manager',
            manageSelector: '.manage',
            messagesSelector: '.messages',
            messagesTimeout: 10000,
            csrfName: undefined,
            csrf: undefined,
            deletePrevention: 'Do you really want to delete the file?'
        },
        /**
         * Элемент, над которым выполняются действия
         */
        element: undefined,
        $element: undefined,
        currentPath: undefined,
        manage: {},
        /**
         * Инициализация
         * @param element
         * @param options
         */
        init: function (element, options) {
            if (element === undefined) return;

            this.element = element;
            this.$element = $(element);
            this.options = $.extend(this.options, options);

            this.currentPath = this.options.startPath;

            this.initManagement();
            this.initUploader();
            this.bind();

            return this;
        },
        initManagement: function () {
            var me = this;

            me.manage['manage'] = $(this.options.manageSelector);
            me.manage['create_folder'] = {
                'button': me.manage.manage.find('.create-folder-button'),
                'input': me.manage.manage.find('.create-folder-input')
            };
        },
        initUploader: function () {
            var me = this;

            var query = {
                'path': this.currentPath
            };
            query[this.options.csrfName] = this.options.csrf;

            var flow = new Flow({
                target: this.options.uploadUrl,
                testChunks: false,
                query: query,
                allowDuplicateUploads: true
            });

            flow.assignBrowse(document.getElementById('select'));
            flow.assignDrop(document.getElementById('zone'));

            flow.on('fileAdded', function(file, event){});

            flow.on('filesSubmitted', function(){
                flow.opts['query']['path'] = me.currentPath;
                flow.upload();
            });

            flow.on('uploadStart', function(){
                $('#progress_bar').css({
                    'width': 0
                });
                me.setUploading();
            });

            flow.on('progress', function(){
                var width = flow.progress() * 100 + '%';
                $('#progress_bar').css({
                    'width': width
                });
            });

            flow.on('complete', function(){
                $('#progress_bar').css({
                    'width': 0
                });
                me.unsetUploading();
                me.updateList();
            });
        },
        /**
         * "Навешиваем" события
         */
        bind: function () {
            var me = this;

            this.$element.on('click', '.files .file-check', function (e) {
                if (!$(e.target).is('input')){
                    e.preventDefault();
                    $(this).find('input').trigger('click');
                    return false;
                }
            });

            //this.$element.on('click', '.files .file-name', function (e) {
            //    e.preventDefault();
            //    $(this).find('a').click();
            //    return false;
            //});

            this.$element.on('click', '.files .file .file-link', function (e) {
                e.preventDefault();
                me.openFile($(this).data('url'));
                return false;
            });

            this.$element.on('click', '.files .dir .file-link', function (e) {
                e.preventDefault();
                me.openFolder($(this).data('path'));
                return false;
            });

            this.$element.on('click', '.files .delete-link', function (e) {
                e.preventDefault();
                if (confirm(me.options.deletePrevention)) {
                    me.deleteFile($(this).data('path'));
                }
                return false;
            });

            this.$element.on('click', '.create-folder-button', function (e) {
                e.preventDefault();
                me.createFolder(me.manage.create_folder.input.val());
                return false;
            });

            this.$element.on('click', '.remove-selected', function(e) {
                e.preventDefault();
                if (confirm(me.options.deletePrevention)) {
                    me.deleteAll();
                }
                return false;
            });

            // var collection = $();
            // $(document).on('dragenter', function (e) {
            //     me.showDropInfo();
            //     collection = collection.add(e.target);
            // }).on('dragleave',function (e) {
            //     collection = collection.not(e.target);
            //     if (!collection.length) {
            //         me.hideDropInfo();
            //     }
            // }).on('drop', function () {
            //     collection = $();
            //     me.hideDropInfo();
            // });
        },
        showDropInfo: function() {
            var fileman = this.$element;
            if (!fileman.hasClass('drop'))
                fileman.addClass('drop');
        },
        hideDropInfo: function() {
            this.$element.removeClass('drop');
        },
        setUploading: function() {
            this.$element.addClass('uploading');
        },
        unsetUploading: function() {
            this.$element.removeClass('uploading');
        },
        openFile: function (url) {
            var me = this;
            $('#' + this.options.field).val(url);
            $('.modal-closer').trigger('click');
        },
        openFolder: function (path) {
            var me = this;
            me.updateList(path);
        },
        createFolder: function (name) {
            var me = this;
            me.api('make', {'name': name}, function (data) {
                if (data.statement == 'success') {
                    me.manage.create_folder.input.val('');
                    me.updateList();
                }
            });
        },
        deleteAll: function() {
            var me = this;
            var files = [];
            $('input.delete-checker:checked').each(function(){
                files.push($(this).val());
            });
            me.api('deleteAll', {'files': files}, function (data) {
                $('input.delete-checker').removeAttr('checked');
                me.updateList();
            });
        },
        deleteFile: function (name) {
            var me = this;
            me.api('delete', {'name': name}, function (data) {
                if (data.statement == 'success') {
                    me.updateList();
                }
            });
        },
        api: function (action, sendData, callback) {
            var me = this;
            sendData = sendData || {};
            if (!sendData.path) {
                sendData.path = me.currentPath
            }
            sendData['action'] = action;
            sendData[me.options.csrfName] = me.options.csrf;
            $.ajax({
                'type': 'post',
                'url': me.options.apiUrl,
                'data': sendData,
                'dataType': 'json',
                'success': function (data) {
                    if (data.statement && data.message) {
                        if (data.statement == 'error') {
                            me.error(data.message);
//                        }else{
//                            me.message(data.message);
                        }
                    }
                    if (callback) {
                        callback(data);
                    }
                }
            })
        },
        message: function (message, type) {
            var me = this;
            type = type || 'message';

            var $notification = $('<div/>').addClass('notification').addClass(type).html(message);
            var $messages = $(me.options.messagesSelector);
            $messages.append($notification);

            setTimeout(function () {
                $notification.remove();
            }, me.options.messagesTimeout)
        },
        error: function (message) {
            this.message(message, 'error');
        },
        updateList: function (path) {
            var me = this;
            path = path || me.currentPath;
            var sendData = {
                'path': path
            };
            $.ajax({
                'url': me.options.listUrl,
                'data': sendData,
                'dataType': 'html',
                'success': function (data) {
                    var wrapped_data = $('<div/>').append(data);
                    $(me.element).find('.files').replaceWith(wrapped_data.find('.files'));
                    me.currentPath = path;
                }
            });
        }
    });

    /**
     * Инициализация функции объекта для jQuery
     */
    return $.fn.fileapi = function (options) {
        return fileapi.init(this, options);
    };

})($);
