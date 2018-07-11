import documentReady from "../utils/documentReady";
import formValidate from "validate.js/validate";

documentReady(() => {

    var constraints = {
        'ProductQuestionForm[firstname]': {
            // You need to pick a username too
            presence: true,
            // And it must be between 3 and 20 characters long
            length: {
                minimum: 3,
                maximum: 20
            },
            format: {
                // We don't allow anything that a-z and 0-9
                pattern: "[a-z0-9]+",
                // but we don't care if the username is uppercase or lowercase
                flags: "i",
                message: "can only contain a-z and 0-9"
            }
        },
        'ProductQuestionForm[email]': {
            // Email is required
            presence: true,
            // and must be an email (duh)
            email: true
        },
        'ProductQuestionForm[phone]': {
            presence: true,
            numericality: {
                onlyInteger: true,
                greaterThanOrEqualTo: 0
            }
        },
        'ProductQuestionForm[phone_ext]': {
            numericality: {
                onlyInteger: true,
                greaterThanOrEqualTo: 0
            }
        }
    };

    // // Hook up the form so we can prevent it from being posted

    // form.addEventListener("submit", function(event) {
    //     event.preventDefault();
    //     handleFormSubmit(form);
    // });

    console.log(formValidate);

    document.addEventListener('form.constructed', function (e) {
        var form = document.querySelector(".send-question form");
        // Hook up the inputs to validate on the fly
        var inputs = document.querySelectorAll("input, textarea, select")
        for (var i = 0; i < inputs.length; ++i) {
            inputs.item(i).addEventListener("change", function(event) {
                //console.log(event);
                console.log(form);
                var errors = formValidate(form, constraints) || {};
                console.log(errors);
                //showErrorsForInput(this, errors[this.name])
            });
        }
    }, false);



    // function handleFormSubmit(form, input) {
    //     // validate the form aainst the constraints
    //     var errors = validate(form, constraints);
    //     // then we update the form to reflect the results
    //     showErrors(form, errors || {});
    //     if (!errors) {
    //         showSuccess();
    //     }
    // }


});