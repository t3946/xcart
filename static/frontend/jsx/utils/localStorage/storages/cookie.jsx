import Cookies from 'js-cookie';

export default class CookieStorage
{
    constructor() {
        // super();
    }

    get(key, def = null) {
        let value;

        value = Cookies.get(key);

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

        Cookies.set(key, value, {expires: expires});
    }

    remove(key) {
        Cookies.remove(key);
    }
}