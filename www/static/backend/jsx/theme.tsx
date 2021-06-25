//скрипты найденные в шаблонах

import $ from "jquery";
import "select2";
import tinymce from "tinymce";
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

  const cruds = appData().app.cruds;

  if (cruds) {
    for (const id in cruds) {
      $(`[data-id="${id}-list"]`).adminList(cruds[id].links);
    }
  }
});

//из app/Modules/Dashboard/templates/order/orders_list.tpl
$("a.select-order").click(function () {
  $(".orders tr").removeClass("selected");
  const orderid = $(this).closest("tr").data("orderid");
  const current = $("tr.order_list_row_" + orderid);
  current.addClass("selected");
  return true;
});
///var/www/html/app/Modules/Dashboard/templates/dashboard/layouts/dashboard_layout.tpl
(function () {
  return;
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

        const term = $.trim(params.term);

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
          const url = "/admin/dashboard/search_suggestion";
          let combobox = 0;
          let delimiter = "?";
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
          const remainingChars = args.minimum - args.input.length;
          const message = "Type at least " + remainingChars + " letters";

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

//инициализация компонентов select2 из разных шаблонов, с разных страниц админки
(function () {
  $(".child-users")
    .select2({
      allowClear: false,
      closeOnSelect: false,
      placeholder: "Click to select Users",
    })
    .on("select2:unselecting", function (e) {
      if (!e.params.args.originalEvent) {
        return false;
      }
      e.params.args.originalEvent.stopPropagation();
    });

  $(".select2-field").each((i, elem) => {
    const $elem = $(elem);
    const { editable, placeholder } = elem.dataset;
    const multiple = elem.getAttribute("multiple") !== null;

    const data: Record<any, any> = {
      multiple,
      tags: editable,
      allowClear: true,
      closeOnSelect: !multiple,
    };

    data.placeholder = placeholder || "Click to select value";
    data.width = "resolve";

    data.createTag = function (params) {
      const term = $.trim(params.term);

      if (term === "") return null;

      return {
        id: term,
        text: term,
        newTag: true,
      };
    };

    //dynamic ajax loading
    if (data.dataUrl) {
      data.ajax = {
        url: data.dataUrl,
        dataType: "json",
        delay: 250,
        processResults(data: any, page) {
          if (data) {
            return {
              results: data.items,
              more: page * 30 < data.total_count,
            };
          }

          return { results: {} };
        },
      };
    }

    $elem.select2(data);
  });
})();

// www/skin1_kolin/modules/Manufacturers/manufacturers.tpl
(function () {
  $(function () {
    $(".tooltip").tooltip({
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
        ui.tooltip.css("max-width", "400px");
      },
    });
  });
})();

// /www/skin1_kolin/admin/main/reconciliation.tpl
(function () {
  $("#net_choises")
    .select2({
      allowClear: true,
      closeOnSelect: false,
      placeholder: $("#net_choises").attr("title"),
    })
    .on("change.select2", function () {
      const distributor_data = [];
      $("option:selected", $("#distributor_choises")).each(function () {
        distributor_data.push($(this).val());
      });
      const data = [];
      $("option:selected", $(this)).each(function () {
        data.push($(this).val());
      });
      $("#distributor_choises").empty().prop("disabled", true);
      $.post(
        "/admin/order/api/payable_manufacturers",
        {
          period: data,
        },
        function (data) {
          let option = "";
          let i = 0;
          $("#distributor_choises").empty();
          for (; i < data.length; i++) {
            option = $("<option/>")
              .attr("value", data[i].manufacturerid)
              .text(data[i].manufacturer);
            if (
              distributor_data.length > 0 &&
              distributor_data.indexOf(data[i].manufacturerid) >= 0
            ) {
              option.prop("selected", true);
            }
            $("#distributor_choises").append(option).prop("disabled", false);
          }
          $("#distributor_choises").change();
        }
      );
    });

  $("#distributor_choises")
    .select2({
      allowClear: true,
      closeOnSelect: false,
      placeholder: $("#distributor_choises").attr("title"),
    })
    .on("change.select2", function () {
      const distributor_data = [];
      $("option:selected", $(this)).each(function () {
        distributor_data.push($(this).val());
      });
      const period_data = [];
      $("option:selected", $("#net_choises")).each(function () {
        period_data.push($(this).val());
      });

      $.post(
        "/admin/order/api/payable_orders",
        {
          period: period_data,
          distributor: distributor_data,
        },
        function (data) {
          $(".distibutor_payable").empty().css("opacity", 1).html(data);
        }
      );
    });

  $(".net_choises__select_all").click(function () {
    const net_choises = $(this).parent().siblings("select");
    net_choises.find("option").prop("selected", "selected");
    net_choises.trigger("change");
    return false;
  });
})();
// /app/Modules/Editor/templates/editor/fields/editor_field_input.tpl
(function () {
  $(".tinymce-field").each((i, elem) => {
    const { readonly, baseUrl, changedUrl } = elem.dataset;

    tinymce.init({
      selector: "#" + elem.id,
      readonly: readonly === "true",
      setup: (editor) => {
        editor.on("change", function () {
          tinymce.triggerSave();
        });
      },
      plugins: [
        "advlist autolink link image autoresize colorpicker autosave lists charmap print preview hr anchor",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime image imagetools media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor contextmenu",
      ],
      relative_urls: false,
      browser_spellcheck: true,
      toolbar:
        "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent  indent | link image",
      inline_boundaries: true,
      forced_root_block: true,
      branding: false,
      height: "480",
      image_advtab: true,
      file_browser_callback: function (field_name, url, type, win) {
        window.file_browser_window = win;
        window.file_browser_field = field_name;
        window.file_browser_url = url;
        window.file_browser_type = type;
        let base_url = baseUrl;
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
        xhr.open("POST", changedUrl);
        xhr.onload = function () {
          let json;
          if (xhr.status != 200) {
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
})();

(function () {
  const url_dashboard_update = appData().routes["dashboard:index"];
  const url_dashboard_my_sort = appData().routes["dashboard:sort_my_filters"];

  $(document).ready(function () {
    $(document).dashboard({
      ajax: {
        url: url_dashboard_update,
      },
    });

    $(".dashboard-filters.index a[data-id]").majaxtooltip({
      onAfterSubmit: function () {
        this.setContent("<div class='load'></div>");
      },
      onAfterSuccess: function () {
        $.mnotify({
          title: '"My dashboard" changed',
          message: "Refresh the page to display\\hide the elements",
        });
      },
    });

    $(".my_dashboard .dashboard-filters ").tablePositions({
      draggableSelector: ".button, .empty",
      dropSelector: ".container",

      onMove: function (el, to) {
        const def = $.Deferred();
        $.ajax({
          type: "POST",
          url: url_dashboard_my_sort,
          data: {
            position_row: $(to).data("row"),
            position_column: $(to).data("col"),
            id: $(el).data("id"),
          },
          success: function (data) {
            if (data) {
              $.mnotify({
                title: "Position saved",
                message: data.message,
              });

              def.resolve(true, data);
            }
            def.reject(false);
          },
          error: function () {
            def.reject(false);
          },
        });

        return def.promise();
      },
    });

    $(".tabs").tabs({ active: 1 });
  });
})();
