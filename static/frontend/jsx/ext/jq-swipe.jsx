(function($) {
    $.event.special.swipe = {
        setup: function () {
            $(this).bind('touchstart', $.event.special.swipe.handler);
        },

        teardown: function () {
            $(this).unbind('touchstart', $.event.special.swipe.handler);
        },

        handler: function (event) {
            var args = [].slice.call(arguments, 1),
                touches = event.originalEvent.touches,
                startX, startY,
                deltaX = 0, deltaY = 0,
                that = this;

            event = $.event.fix(event);

            function cancelTouch() {
                that.removeEventListener('touchmove', onTouchMove);
                startX = startY = null;
            }

            function onTouchMove(e) {
                var Dx = startX - e.touches[0].pageX,
                    Dy = startY - e.touches[0].pageY,
                    minPath = window['swipe_min_path'] | 100;


                if (Math.abs(Dx) >= minPath) {
                    cancelTouch();
                    deltaX = (Dx > 0) ? -1 : 1;
                }
                else if (Math.abs(Dy) >= minPath) {
                    cancelTouch();
                    deltaY = (Dy > 0) ? 1 : -1;
                }

                if (deltaX !== 0) {
                    e.preventDefault();
                }

                event.type = "swipe";
                args.unshift(event, deltaX, deltaY); // add back the new event to the front of the arguments with the delatas
                return ($.event.dispatch || $.event.handle).apply(that, args);
            }

            if (touches.length === 1) {
                startX = touches[0].pageX;
                startY = touches[0].pageY;
                this.addEventListener('touchmove', onTouchMove, false);
            }
        }
    };
})($);