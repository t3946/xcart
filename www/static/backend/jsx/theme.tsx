//скрипты найденные в шаблонах перемещены в этот файл с целью отделить разметку от скриптов

import $ from "jquery";
import "select2";
import tinymce from "tinymce";
import appData from "@admin/utils/app-data";
import InitSelect2 from "@admin/utils/init-select2";

$(document).ready(function () {
  $("#select_searchstring_by").change(function () {
    const select_searchstring_by = $("#select_searchstring_by").val();
    $("#searchstring").attr("name", "search" + select_searchstring_by);
  });
});

$(function () {
  const t = $(".field-tooltip").tooltip({
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
    InitSelect2(elem);
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

// from /app/Modules/Reports/templates/reports/layouts/search_layout.tpl
(function () {
  $(".shapeshift .shapeshift-container")
    .shapeshift({
      colWidth: 200,
    })
    .on("ss-rearranged ss-added ss-removed", function (e, selected) {
      $("> div", $(this)).each(function (i, elem) {
        $(elem).attr("data-index", ++i);
      });
    });
  $("#report_form").submit(function (e) {
    const submit_form = $(this).closest("form");
    $("input.hidden_groups", submit_form).remove();
    const containers = $(".shapeshift .shapeshift-container.for-save");
    containers.each(function () {
      const cur_container = $(this);
      $(this)
        .find("> div")
        .each(function () {
          const input = $("<input>")
            .attr("type", "hidden")
            .addClass("hidden_groups")
            .attr(
              "name",
              "search[report][" +
                cur_container.attr("data-param-name") +
                "][" +
                $(this).attr("data-index") +
                "]"
            )
            .val($(this).attr("data-model"));
          submit_form.append($(input));
        });
    });
  });
})();

// /html/app/Modules/Goods/templates/verification/all.tpl
$(function () {
  if (!appData().goodsModule) {
    console.log("no goodsModule");
    return;
  }

  $(".admin-page").on("change", "select[id$=verification_status]", function () {
    const status_id = parseInt($(this).val());
    const product_id = parseInt($(this).closest("tr").data("pk"));
    const note_form = $("#send_note_for_product");
    const textarea = note_form.find("textarea");
    if (status_id > 0 && status_id < 3) {
      const position = $(this).offset();
      note_form.css("right", 0).css("top", position.top - 150);
      textarea.val("");
      if (status_id === 1) {
        textarea.attr(
          "placeholder",
          "Please describe the problem and explain why you didn't fix it."
        );
      }
      if (status_id === 2) {
        textarea.attr(
          "placeholder",
          "Please describe what was the problem and how did you fix it."
        );
      }
      note_form.find("#verified_product_id").val(product_id);
      note_form.find("#verified_product_status_id").val(status_id);
      note_form.show();
      textarea.focus();
    } else {
      const id = appData().goodsModule.id;
      const list = $('[data-id="' + id + '-list"]').data("object");
      list.setLoading();
      $.post(
        "/api/products/verify",
        {
          product_id: product_id,
          status_id: status_id,
        },
        (data) => {
          if (data && data.result) {
            list.update();
          } else {
            list.unsetLoading();
          }
        }
      );
    }
  });

  $("#send_note_for_product")
    .on("click", "#cancel_message_button", () => {
      $("#send_note_for_product").hide();
    })
    .on("click", "#post_message", () => {
      const product_id = parseInt($("#verified_product_id").val());
      const status_id = parseInt($("#verified_product_status_id").val());
      const form = $("#send_note_for_product");
      const textarea = form.find("textarea");
      form.hide();
      const id = appData().goodsModule.id;
      const list = $('[data-id="' + id + '-list"]').data("object");
      list.setLoading();
      $.post(
        "/api/products/verify",
        {
          product_id: product_id,
          status_id: status_id,
          note_text: textarea.val(),
        },
        (data) => {
          if (data && data.result) {
            list.update();
          } else {
            list.unsetLoading();
          }
        }
      );
    });
});

// /www/skin1_kolin/admin/main/paypal_request.tpl
$('#main_order_tabs-container').on('tabsactivate', function(event, ui) {
  if (ui.newTab.find('a').attr('href') === '#main_order_tabs-paypal_request'){
    $('.invoice_list_row[data-status=new]').each(function(){
      var row = $(this);
      var inv_number = $(this).find('.pp_invoice_number').data('inv-number');
      row.find('.inv_status').addClass('active').text('');
      $.post('ajax_admin.php', {
            ajax_action: 'get_paypal_invoice_status',
            paypal_invoice_id: inv_number
          },
          function (data) {
            if (data.result) {
              row.attr('data-status', 'updated');
              row.find('.inv_status').removeClass('active').removeClass('ui').text(data.status);
            }
          }, 'json');
    })
  }
});

const paypal_form = $('.ui.form.paypal_request');
if (paypal_form.length) {
  $.fn.form.settings.rules.gtzero = function(value) {
    return (value > 0)
  };
  paypal_form
      .form({
        onValid: function(){
          $('.ui.error.message').empty();
        },
        onSuccess: function () {
          $('.ui.error.message').empty();
          if ($('#order_email').val() != $('#paypal_request_email').val()) {
            if (!window.confirm("Payer email is different from order's email. Are you sure?")) {
              return false;
            }
          }
          var form = $(this);
          var param = form.css('opacity', 0.4).find('.ui.loader').addClass('active').end().serializeArray();
          form.find('#send_paypal_request').attr('disabled', 'disabled');
          param.push({name: 'ajax_action', value: 'send_paypal_request'});
          $.post('ajax_admin.php', param,
              function (data) {
                form.css('opacity', 1).find('.ui.loader').removeClass('active').end().find('#send_paypal_request').removeAttr('disabled');
                if (data.result) {
                  form.find('#paypal_request_amount').val('0.00').end()
                      .find('#paypal_request_notes').val('').end();
                  alert('The Invoice has been send');
                } else {
                  alert('An error occurred');
                }
                window.location.reload();
              }, 'json');
          return false;
        },
        fields: {
          paypal_request_email: {
            identifier  : 'paypal_request_email',
            rules: [
              {
                type   : 'empty',
                prompt : '<b>Payer email</b>: Mandatory field is empty!'
              },
              {
                type   : 'email',
                prompt : '<b>Payer email</b>: Email address is incorrect'
              }
            ]
          },
          paypal_request_subject: {
            identifier  : 'paypal_request_subject',
            rules: [
              {
                type   : 'empty',
                prompt : '<b>Payment Request subject</b>: Mandatory field is empty!'
              }
            ]
          },
          paypal_request_notes: {
            identifier  : 'paypal_request_notes',
            rules: [
              {
                type   : 'empty',
                prompt : '<b>Short payment description</b>: Mandatory field is empty!'
              }
            ]
          },
          paypal_request_amount: {
            identifier  : 'paypal_request_amount',
            rules: [
              {
                type   : 'empty',
                prompt : '<b>Request amount</b>: Mandatory field is empty!'
              },
              {
                type   : 'regExp[/^[0-9]*[.]{0,1}[0-9]{0,2}$/]',
                prompt : '<b>Request amount</b>: Value is incorrect!'
              },
              {
                type   : 'gtzero',
                prompt : '<b>Request amount</b>: Value must be greater then 0!'
              }
            ]
          }
        }
      });
}

