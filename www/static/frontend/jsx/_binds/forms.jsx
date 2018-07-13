import documentReady from "../utils/documentReady";
import formValidation from "../components/FormValidation";

documentReady(() => {

    if (typeof document.formConstraints !== 'undefined') {
        for (let name in document.formConstraints) {
            formValidation(name);
        }
    }

    document.addEventListener('form.client.validation', function (event) {
        formValidation(event.detail);
    }, false);
});