import documentReady from "../utils/documentReady";
import formValidation from "../components/FormValidation";
import clearFormFields from "../components/ClearFormFields";

function createDuplicatedFields(fields){

    for (let fieldName in fields) {
        let fieldObj = fields[fieldName];
        fieldObj.element.addEventListener('form_validation.success', function(event){
            let duplicateId = event.target.dataset.duplicate || null;
            if (duplicateId && duplicateId.length) {
                let duplicateElement = document.getElementById(duplicateId);
                let dElementName = duplicateElement.getAttribute('name');
                let dFieldObj = fields[dElementName];
                if (!duplicateElement.value.length) {
                    duplicateElement.value = event.detail.value;
                    dFieldObj.success();
                }
            }
        });
    }
}

documentReady(() => {

    // init form client validation
    if (typeof document.formConstraints !== 'undefined') {
        for (let name in document.formConstraints) {
            let form = formValidation(name);
            createDuplicatedFields(form.fields);
        }
    }

    document.addEventListener('form.client.validation', function (event) {
        let form = formValidation(event.detail);
        createDuplicatedFields(form.fields);
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
});