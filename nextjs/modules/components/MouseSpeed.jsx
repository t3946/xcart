import extend from "lodash/extend";

export default class MouseSpeed {
  constructor(options) {
    this.options = {
      refreshinterval: 500,
    };

    if (options) {
      extend(this.options, options);
    }

    this.lastmousex = -1;
    this.lastmousey = -1;
    this.lastmousetime = null;
    this.mousetravel = 0;
    this.speed = 0;

    this.init();
  }

  init() {
    this._bind();

    this.startTimer();
  }

  calcSpeed() {
    let time = new Date().getTime();

    if (this.lastmousetime && this.lastmousetime !== time) {
      this.speed = Math.round(
        (this.mousetravel / (time - this.lastmousetime)) * 1000
      );

      this.mousetravel = 0;
    }
    this.lastmousetime = time;
    this.startTimer();
  }

  startTimer() {
    setTimeout(() => {
      $(document).trigger("ms.tick");
    }, this.options.refreshinterval);
  }

  _bind() {
    $(document).mousemove((e) => {
      let mousex = e.pageX;
      let mousey = e.pageY;
      if (this.lastmousex > -1)
        this.mousetravel += Math.max(
          Math.abs(mousex - this.lastmousex),
          Math.abs(mousey - this.lastmousey)
        );
      this.lastmousex = mousex;
      this.lastmousey = mousey;
    });

    $(document).on("ms.tick", () => {
      this.calcSpeed();
    });
  }

  getSpeed() {
    return this.speed;
  }
}
