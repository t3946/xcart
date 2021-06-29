import AutoComplete from "javascript-auto-complete";

/**
 * Обёртка для модуля javascript-auto-complete. Позволяет добавлять/изменять функционал в поведение модуля,
 * не внося изменение в код использующий модуль -- модуль промежуточной обработки
 */

export class ShippingPixabayAutocomplete {
  /**
   * @param elem -- input selector
   * @param autocompleteOptions -- options for AutoComplete module
   */
  constructor(elem, autocompleteOptions) {
    this.variants = null;
    this.forceCompleted = false;
    this.input = null;
    this.input = typeof elem === "string" ? document.querySelector(elem) : elem;

    new AutoComplete({
      selector: this.input,
      cache: false,
      offsetTop: 0,
      minChars: 1,
      renderItem: autocompleteOptions.renderItem,

      /**
       * конфигурирует список релевантных данных
       *
       * служит обёрткой для переданной функции того же назначения
       *
       * @param term -- строка, введённая в поле ввода
       * @param suggest -- функция принимающая релевантные term данные для дальнейшей обработки плагином
       * @return void
       */
      source: function (term, suggest) {
        autocompleteOptions.source(term, (data) => {
          suggest(data);
        });
      },

      /**
       * A callback function that fires when a suggestion is selected by mouse click, enter, or tab.
       *
       * @param e -- the event that triggered the callback
       * @param term -- the selected value
       * @param item -- the item(html node) rendered by the renderItem function.
       * @return void
       */
      onSelect: function (e, term, item) {
        if (typeof autocompleteOptions.onSelect === "function") {
          autocompleteOptions.onSelect(e, term, item);
        }
      },
    });
  }
}
