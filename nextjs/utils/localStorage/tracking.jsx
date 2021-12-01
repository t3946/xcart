'use strict';

var listeners = {};
var listening = false;

function listen () {
    if (global.addEventListener) {
        global.addEventListener('storage', change, false);
    }
    else if (global.attachEvent) {
        global.attachEvent('onstorage', change);
    }
    else {
        global.onstorage = change;
    }
}

function change (e) {
    if (!e) {
        e = global.event;
    }
    var all = listeners[e.key];
    if (all) {
        all.forEach(fire);
    }

    function fire (listener) {
        listener(e.newValue, e.oldValue, e.url || e.uri);
    }
}

export function on (key, fn) {
    if (listeners[key]) {
        listeners[key].push(fn);
    } else {
        listeners[key] = [fn];
    }
    if (listening === false) {
        listen();
    }
}

export function off (key, fn) {
    var ns = listeners[key];
    if (ns.length > 1) {
        ns.splice(ns.indexOf(fn), 1);
    } else {
        listeners[key] = [];
    }
}

export default {
    on: on,
    off: off
};