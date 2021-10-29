import i18n from "i18next";
import { initReactI18next } from "react-i18next";

i18n.use(initReactI18next).init({
  resources: {
    en: {
      translation: app.options.translates,
    },
  },

  interpolation: {
    prefix: "%",
    suffix: "%",
    escapeValue: false,
  },

  lng: "en",
  pluralSeparator: "_",
  fallbackLng: "ru",

  missingKeyHandler: function (lng, ns, key, fallbackValue) {
    console.log("MISSING", lng, ns, key, fallbackValue);
  },
  returnedObjectHandler: function (key, value, options) {
    console.log("RETURNED", key, value, options);
  },

  // return key as value if value is empty
  returnEmptyString: false,
});

export default function (...options) {
  return i18n.t(...options);
}
