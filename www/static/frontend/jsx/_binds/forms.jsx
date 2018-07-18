import documentReady from "../utils/documentReady";
import formValidation from "../components/FormValidation";
import clearFormFields from "../components/ClearFormFields";

documentReady(() => {

    // init form client validation
    if (typeof document.formConstraints !== 'undefined') {
        for (let name in document.formConstraints) {
            formValidation(name);
        }
    }

    document.addEventListener('form.client.validation', function (event) {
        formValidation(event.detail);
    }, false);


    // init clear fields
    if (typeof document.formClearFields !== 'undefined') {
        for (let name in document.formClearFields) {
            clearFormFields(name);
        }
    }

    document.addEventListener('form.client.fields.clear', function (event) {
        clearFormFields(name);
    }, false);


});