import "whatwg-fetch";
import isArray from "lodash/isArray";
import isObject from "lodash/isObject";
import each from "lodash/each";

let prepareUrl = (url) =>
  (url += (url.indexOf("?") ? "?" : "&") + "__=" + new Date().getTime());
let isJsonResponse = (response, isJson = false) =>
  isJson ||
  response.headers.get("Content-Type").toLowerCase() === "application/json";

let paramsToForm = (data, form = new FormData()) => serialize(form, data);

let serialize = (form, obj, traditional, scope) => {
  let type,
    array = isArray(obj),
    hash = isObject(obj);

  each(obj, (value, key) => {
    type = typeof value;
    if (scope) {
      key = traditional
        ? scope
        : scope +
          "[" +
          (hash || type === "object" || type === "array" ? key : "") +
          "]";
    }

    if (!scope && array) {
      form.append(key, value);
    } else if (type === "array" || (!traditional && type === "object")) {
      serialize(form, value, traditional, key);
    } else {
      form.append(key, value);
    }
  });

  return form;
};

export default (url, data, success, error) => {
  let isJson = false;
  let options = {
    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
    credentials: "same-origin", // include, same-origin, *omit
    headers: {
      "X-REQUESTED-WITH": "XMLHttpRequest",
    },
    method: "GET", // *GET, POST, PUT, DELETE, etc.
    mode: "same-origin", // no-cors, cors, *same-origin
    redirect: "follow", // manual, *follow, error
    referrer: "no-referrer", // *client, no-referrer
  };

  if (data) {
    if (data.type) {
      isJson = data.type.toLowerCase() === "json";
    }

    if (data.data) {
      options["body"] = paramsToForm(data.data);
    }

    if (data.forceNoCache) {
      url = prepareUrl(url);
    }

    options["method"] = data.method ? data.method.toUpperCase() : "GET";
    options["mode"] = data.mode ? data.method.toLowerCase() : "same-origin";
  }

  return fetch(url, options).then(
    (response) => {
      let pr = isJsonResponse(response, isJson)
        ? response.json()
        : response.text();

      pr.then(success, error);

      return pr;
    },
    (response) => error(response)
  );
};
