jQuery.fn.mfieldset = function (options) {
    "use strict";

    // настройки по умолчанию
    var options = jQuery.extend({
        animation: false,
        speed: 'fast'
    }, options);
    return this.each(function (i) {

        var fieldset = $(this),
            legend = fieldset.children('legend'),
            indexVar = fieldset.parent().children('fieldset').index(this),
            cookieVar = $.cookie('fieldset_' + indexVar);

        fieldset.attr('rel', indexVar);

        if ((cookieVar == '1' || i === 0) && !fieldset.hasClass('collapsed-force')) {
            showFieldsetContent(fieldset, options);
        } else
            hideFieldsetContent(fieldset, {animation: false});

        $(legend).bind('click', function () {
            if (fieldset.hasClass('expanded')) {
                $.cookie('fieldset_' + indexVar, null, {expires: -1});
                hideFieldsetContent(fieldset, options);
                fieldset.removeClass('expanded').addClass('collapsed');
            } else {
                $.cookie('fieldset_' + indexVar, 1, {expires: 365});
                showFieldsetContent(fieldset, options);
                fieldset.removeClass('collapsed').addClass('expanded');
            }
        });

        function hideFieldsetContent(obj, options) {
            if (options.animation == true)
                obj.find(":eq(1)").slideUp(options.speed);
            else
                obj.find(":eq(1)").hide();

            obj.removeClass("expanded").addClass("collapsed");
        }

        function showFieldsetContent(obj, options) {
            if (options.animation == true)
                obj.find(":eq(1)").slideDown(options.speed);
            else
                obj.find(":eq(1)").show();

            obj.removeClass("collapsed").addClass("expanded");
        }
    });
};

(function(){
    "use strict";

    $(document).ready(function(){
        $('fieldset').mfieldset();

    });
})();
