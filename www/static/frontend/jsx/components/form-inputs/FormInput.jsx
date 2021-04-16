import React from "react";
import {Field} from "formik";

const Input = ({ name, label, type = "text", required = false, error = false, errorMessage}) => {
    return (
        <div>
            <div><span>{label}</span>{required ? <span>*</span> : null}</div>
            <input type={type} name={name} required={required} placeholder={label}/>
            {error ? <span>{errorMessage}</span> : null}
        </div>
    )
}

const FormInput = ({ name, label, type = "text", required = false, error = false, errorMessage}) => {
    return (
        <div className="FormikField">
            <Field
                required={required}
                autoComplete="off"
                error={error}
                as={Input}
                label={label}
                name={name}
                fullWidth
                type={type}
                helperText={errorMessage}
            />
        </div>
    );
};

export default FormInput;