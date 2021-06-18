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
        //TODO: требуется восстановление пути
        let base_url = ""; //"{url route="editor:index"}";
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
        //TODO: требуется восстановление пути
        //xhr.open('POST','{url route="editor:changed"}');
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
$(".actions").on("click", function () {
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

$(function () {
  const id = appData().app.id;

  $(`[data-id="${id}-list"]`).adminList(appData().app.cron);
});
