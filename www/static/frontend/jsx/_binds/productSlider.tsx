import React from "react";
import Slider from "@client/jsx/components/product/ImagesSlider/Slider";
import { Provider } from "react-redux";
import Store from "@client/jsx/redux/stores/Store";
import $ from "jquery";

(() => {
  $(".product__images-slider").each((i, item) => {
    const data = $(item).find("datalist");

    if (data.length) {
      const items = [];

      const options = data.find("option");

      if (options.length) {
        options.each((n, option) => {
          const type = option.getAttribute("type").toLowerCase();

          if (type === "image") {
            items.push({
              type: type,
              src: option.value,
              id: option.dataset.id || null,
              alt: option.dataset.alt || null,
              title: option.dataset.title || null,
              thumb: option.dataset.thumb || null,
              preview: option.dataset.preview || null,
              width: parseInt(option.dataset.width),
              height: parseInt(option.dataset.height),
            });
          }

          if (type === "video") {
            items.push({
              type: type,
              href: option.value,
              alt: option.dataset.alt || null,
              title: option.dataset.title || null,
              img: option.dataset.poster || null,
              thumb: option.dataset.thumb || null,
            });
          }

          if (type === "html") {
            items.push({
              type: type,
              html: option.innerHTML,
              title: option.dataset.title || null,
              thumb: option.dataset.thumb || null,
            });
          }
        });

        // eslint-disable-next-line @typescript-eslint/ban-ts-comment
        // @ts-ignore
        React.render(
          <Provider store={Store as any}>
            <Slider items={items} />
          </Provider>,
          item
        );
      }
    }
  });
})();
