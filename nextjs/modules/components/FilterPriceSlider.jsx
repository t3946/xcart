import extend from "lodash/extend";

export default class FilterPriceSlider {
  constructor(slider, inputs, options = {}) {
    this.slider = slider;
    this.inputs = inputs;
    this.options = extend(
      {
        cssPrefix: "range-",
        format: wNumb({
          decimals: 0,
        }),
        // behaviour: 'tap-snap-drag',
        start: [0, 100],
        connect: true,
        range: {
          min: 0,
          max: 100,
        },
      },
      options
    );

    this.init();
  }

  init() {
    noUiSlider.create(this.slider, this.options);

    this._bind();
  }

  setSliderHandle(i, value) {
    let r = [null, null];
    r[i] = value;
    this.slider.noUiSlider.set(r);
  }

  _bind() {
    this.slider.noUiSlider.on("update", (values, handle) => {
      this.inputs[handle].value = values[handle];
    });

    this.inputs.forEach((input, handle) => {
      input.addEventListener("change", (e) => {
        this.setSliderHandle(handle, e.target.value);
      });

      input.addEventListener("keydown", (e) => {
        let values = this.slider.noUiSlider.get();
        let value = Number(values[handle]);
        let steps = this.slider.noUiSlider.steps();
        let step = steps[handle];
        let position;

        switch (e.which) {
          case 13:
            this.setSliderHandle(handle, e.target.value);
            break;

          case 38:
            position = step[1];

            if (position === false) {
              position = 1;
            }

            if (position !== null) {
              this.setSliderHandle(handle, value + position);
            }

            break;
          case 40:
            position = step[0];

            if (position === false) {
              position = 1;
            }

            if (position !== null) {
              this.setSliderHandle(handle, value - position);
            }

            break;
        }
      });
    });
  }
}
