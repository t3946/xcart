import CookieStorage from './storages/cookie';
import LocalStorage from './storages/local';
import { on , off } from './tracking';


let ls = 'localStorage' in global && global.localStorage ? new LocalStorage() : new CookieStorage();

ls.on = on;
ls.off = off;

export default ls;