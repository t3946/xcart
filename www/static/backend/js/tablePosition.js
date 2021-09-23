
jQuery.fn.tablePositions = function (options) {
    "use strict";
    var option = jQuery.extend({
        draggableSelector: 'a',
        hoveredSelector: 'table',
        dropSelector: 'td',
        onMove: null
    }, options);

    var globals = [];

    function move(elem, to) {
        setTimeout(function () {
            $(to).removeClass('drag-over');
            $(to).append($(elem).detach());
            // $(to).append(elem);

            show(elem);
        }.bind(to, elem), 800);
    }

    function show(elem) {
        setTimeout(function () {
            $(elem).removeClass('move-event');
        }.bind(elem), 20);
    }


    return this.each(function (i) {
        var $container = $(this);

        globals[i] = {
            original: null,
            table: this,
            stop: true
        };

        // $container.find(option.hoveredSelector).on('dragover', function (e) {
        //     // e.originalEvent.dataTransfer.animation = 'move';
        //     $(this).closest(option.hoveredSelector).addClass('hovered');
        //     return (e.target.innerHTML.trim() != '');
        // });

        $container.find(option.dropSelector)
            .on('dragover', function (e) {
                // e.originalEvent.dataTransfer.animation = 'move';
                // $(this).closest(option.hoveredSelector).addClass('hovered');
                return (e.target.innerHTML.trim() != '');
            })
            .on('dragenter', function (e) {
                var has = (e.target.innerHTML.trim() != '');
                if (!has) {
                    $(this).addClass('drag-over');
                }

                return has;
            })
            .on('dragleave', function (e) {
                $(this).removeClass('drag-over');
                // $(this).closest(option.hoveredSelector).removeClass('hovered');
            })

            // .on('drag', function(e) {
            //     if (e.stopPropagation) {
            //         e.stopPropagation();
            //     }
            //     // $(this).closest(option.hoveredSelector).addClass('hovered');

            // })

            .on('drop', function (e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }
                $(this).removeClass('drag-over');
                // $(this).closest(option.hoveredSelector).removeClass('hovered');

                if (this != globals[i].original) {
                    var o_el = globals[i].original;
                    $(o_el).addClass('move-event');

                    if (typeof option.onMove == 'function') {
                        var to = this;
                        // var parent = $(o_el).parent(option.dropSelector);
                        // var t_el = o_el.detach();
                        // $.when(option.onMove(o_el, this))

                        $.when(option.onMove(o_el, to)).done(function(arg){
                            if (arg || arg == 'undefined') {
                                move(o_el, to);
                            }
                        }).fail(function(){
                            show(o_el);
                        });
                    }
                    else {
                        move(o_el, this);
                    }
                }

                return false;
            })
        ;

        $container.find(option.dropSelector + ' > ' + option.draggableSelector)
            .on('dragstart', function (e) {
                globals[i].original = this;

                $(globals[i].table).addClass('drag');
            })

            .on('dragend', function (e) {
                $(globals[i].table).removeClass('drag');
                globals[i].stop = true;
            })
        ;
    });
};