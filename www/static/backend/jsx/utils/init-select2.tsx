// eslint-disable-next-line @typescript-eslint/explicit-module-boundary-types
import $ from "jquery";

/**
 * общая функция инициализации select2 компонентов.
 * все инициализации select2 компонентов должны быть сделаны через эту функцию
 */
export default function InitSelect2(elem: any): void {
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
}
