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
        clearFormFields(event.detail);
    }, false);


    let f_fields = document.querySelectorAll('.form-field');

    for (let i = 0; f_fields.length > i; ++i) {
        f_fields[i].addEventListener('form_validation.success', function(event){
            let d_id = event.target.querySelector('input').dataset.duplicate || null;
            if (d_id && d_id.length) {
                let fname = document.getElementById(d_id);
                if (!fname.value.length) {
                    fname.value = event.detail.value;
                    event.detail.callback(fname);
                }
            }
        });
    }
});