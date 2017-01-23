(function(){
    "use strict";

    $('fieldset > legend').on('click',function(){
        $(this).closest('fieldset').toggleClass('collapsed');

    })
})();