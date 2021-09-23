class StorageInterface
{
    constructor() {
        this.LocalStorageChecked = null;
    }

    hasLocalStorage() {
        if (this.LocalStorageChecked === null) {
            let test = 'test';
            try {
                localStorage.setItem(test, test);
                localStorage.removeItem(test);
                this.LocalStorageChecked = true;
            } catch (e) {
                this.LocalStorageChecked = false;
            }
        }

        return this.LocalStorageChecked;
    }

    get(key, def = null) {
        let value;

        if (this.hasLocalStorage()) {
            value = localStorage.getItem(key);
        }
        else {
            value = $.cookie(key);
        }

        if (!value) {
            value = def;
        }
        return value;
    }

    set(key, value, expires) {
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
    }

    remove(key) {
        if (this.hasLocalStorage()) {
            localStorage.removeItem(key);
        }
        else {
            $.cookie(key, null, {expires: -1});
        }
    }
}
export default new StorageInterface();