//скрипты найденные в шаблонах

import $ from "jquery";
import appData from "@admin/utils/app-data";

$(document).ready(function () {
  $("#select_searchstring_by").change(function () {
    const select_searchstring_by = $("#select_searchstring_by").val();
    $("#searchstring").attr("name", "search" + select_searchstring_by);
  });
});

$(function () {
  const t = $(".tooltip").tooltip({
    position: {
      using: function (position, feedback) {
        $(this).css(position);
        $("<div>").addClass("tooltip__s3").appendTo(this);
      },
    },
    content: function () {
      return $(this).attr("title");
    },
    open: function (event, ui) {
      ui.tooltip.css("max-width", "650px");
    },
    hide: { delay: 1000 },
  });
});

$(function () {
  const flashMessages = appData().app.flash;

  window["flashStack"] = [];

  for (const flashMessage of flashMessages) {
    //TODO: тут могут быть данные в json
    window["flashStack"].push(flashMessage);
  }

  $(document).ready(function () {
    tinymce.init({
      selector: "textarea.new_editor",
      plugins: [
        "advlist autolink link image colorpicker autosave lists charmap print preview hr anchor",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime image imagetools media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor contextmenu",
      ],
      // content_css: '/static/frontend/dist/css/main.css?t=' + new Date().getTime(),
      relative_urls: false,
      browser_spellcheck: true,
      toolbar:
        "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent  indent | link image",
      height: "480",
      branding: false,
      forced_root_block: false,
      file_browser_callback: function (field_name, url, type, win) {
        window.file_browser_window = win;
        window.file_browser_field = field_name;
        window.file_browser_url = url;
        window.file_browser_type = type;
        let base_url = appData().app.tinymce.editorIndex;
        base_url += base_url.indexOf("?") !== -1 ? "&" : "?";

        $("<a/>")
          .attr("href", base_url + "field=" + field_name + "&url=" + url)
          .modal();

        return false;
      },
      images_upload_handler: function (blobInfo, success, failure) {
        let xhr, formData;
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open("POST", appData().app.tinymce.editorChanged);
        xhr.onload = function () {
          let json;
          if (xhr.status !== 200) {
            failure("HTTP Error: " + xhr.status);
            return;
          }
          json = JSON.parse(xhr.responseText);
          success(json.url);
        };
        formData = new FormData();
        formData.append("file", blobInfo.blob(), blobInfo.filename());
        xhr.send(formData);
      },
    });
  });
});

// из /Modules/Admin/templates/admin/list/_email_list.tpl
$(".email-list .actions").on("click", function () {
  const a = $("a", $(this));
  const i = $("i", a);
  const id = $(this).closest("tr").data("thread-id");
  const child = $(".child[data-thread-id=" + id + "]");
  if (i.hasClass("fa-plus")) {
    child.show();
    i.addClass("fa-minus").removeClass("fa-plus");
    event.stopPropagation();
    return false;
  } else {
    child.hide();
    i.addClass("fa-plus").removeClass("fa-minus");
    event.stopPropagation();
    return false;
  }
});

//сортировка и редактирование в крудах, например в фидах
$(function () {
  if (appData().app) {
    const { id, cron } = appData().app;

    $(`[data-id="${id}-list"]`).adminList(cron);
  }
});

//из app/Modules/Dashboard/templates/order/orders_list.tpl
$("a.select-order").click(function () {
  $(".orders tr").removeClass("selected");
  let orderid = $(this).closest("tr").data("orderid");
  let current = $("tr.order_list_row_" + orderid);
  current.addClass("selected");
  return true;
});
///var/www/html/app/Modules/Dashboard/templates/dashboard/layouts/dashboard_layout.tpl
(function () {
  const $select = $(".dashboard-search-form select");

  $select
    .filter("[data-ajax-from]")
    .on("select2:select", function (e) {
      $(this).append(
        $("option[selected]", {
          value: e.params.data.id,
          text: e.params.data.text,
        })
      );
    })
    .select2({
      allowClear: true,
      placeholder: "Click to type and select",
      tags: true,
      closeOnSelect: false,
      minimumInputLength: 3,
      createTag: function (params) {
        if (!this.$element.data("combobox")) {
          return null;
        }

        var term = $.trim(params.term);

        if (term === "") {
          return null;
        }

        return {
          id: appData().app.manualString + term,
          text: appData().app.manualString + term,
        };
      },
      ajax: {
        cache: true,
        dataType: "json",
        delay: 500,
        url: function (params) {
          // var url = '{url 'dashboard:search_suggestion'}';
          var url = "/admin/dashboard/search_suggestion";
          var combobox = 0;
          var delimiter = "?";
          if ($(this).data("combobox")) {
            combobox = 1;
          }

          if (url.indexOf(delimiter, 0) !== -1) {
            delimiter = "&";
          }

          return (
            url +
            delimiter +
            "from=" +
            $(this).data("ajax-from") +
            "&combobox=" +
            combobox
          );
        },
        processResults: function (data) {
          if (data) {
            return {
              results: data,
            };
          }
          return { results: {} };
        },
      },
      language: {
        inputTooShort: function (args) {
          var remainingChars = args.minimum - args.input.length;
          var message = "Type at least " + remainingChars + " letters";

          return message;
        },
      },
    });

  $select
    .filter(":not([data-ajax-from])")
    .not(".page-size select, .not-select2")
    .select2({
      allowClear: true,
      closeOnSelect: false,
      placeholder: "Click to select",
    });
})();
