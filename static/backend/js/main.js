storage = {
    LocalStorageChecked: null,
    hasLocalStorage: function (){
        // "use strict";

        if (this.LocalStorageChecked === null)
        {
            var test = 'test';
            try {
                localStorage.setItem(test, test);
                localStorage.removeItem(test);
                this.LocalStorageChecked = true;
            } catch(e) {
                this.LocalStorageChecked = false;
            }
        }

        return this.LocalStorageChecked;
    },

    get: function (key, def) {
        if (def == 'undefined') {
            def = null;
        }

        if (this.hasLocalStorage()) {
            value = localStorage.getItem(key);
        }
        else {
            var value = $.cookie(key);
        }

        if (!value) {
            value = def;
        }
        return value;
    },

    set: function (key, value, expires) {
        if (value === null) {
            this.remove(key);
            return;
        }

        if (this.hasLocalStorage()) {

            localStorage.setItem(key, value)
        }
        else {
            $.cookie(key, value, {expires: expires});
        }
    },

    remove: function (key) {
        if (this.hasLocalStorage()) {
            localStorage.removeItem(key);
        }
        else {
            $.cookie(key, null, {expires: -1});
        }
    }
};