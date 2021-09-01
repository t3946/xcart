import isArray from 'lodash/isArray';
import isObject from 'lodash/isObject';

function dedupArray(arr)
{
    let t =  [];
    for (let i in arr) {
        if (t.indexOf(arr[i]) === -1) {
            t.push(arr[i]);
        }
    }

    return t;
}

function dedupObj(obj)
{
    for (let key in obj) {
        if (obj.hasOwnProperty(key)) {
            obj[key] = dedup(obj[key]);
        }
    }

    return obj;
}

export default function dedup(val)
{
    if (val) {
        if (isArray(val)) {
            val = dedupArray(val);
        }
        else if (isObject(val)) {
            val = dedupObj(val);
        }
    }

    return val;
}