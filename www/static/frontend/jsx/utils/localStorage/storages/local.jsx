export default class LocalStorage
{
    constructor() {
        this.store = global.localStorage;
    }

    get(key, def = null) {
        let value;

        value = this.store.getItem(key);

        if (!value) {
            value = def;
        }
        return value;
    }

    set(key, value, expires = 30) {
        if (value === null) {
            this.remove(key);
            return;
        }

        this.store.setItem(key, value)
    }

    remove(key) {
        this.store.removeItem(key);
    }
}