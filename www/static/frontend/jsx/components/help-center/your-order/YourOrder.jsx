import React from 'preact'
import * as Yup from "yup";
import {Form, Formik} from "formik";
import FormInput from "@/components/form-inputs/FormInput";

const YourOrder = () => {
    const initialFormValue = {
        name: '',
        email: '',
        phone: '',
        question: '',
    }

    const categoryFormValidationSchema = Yup.object().shape({
        name: Yup.string().required("Обязательное поле"),
        email: Yup.string().required("Обязательное поле"),
        phone: Yup.string().required("Обязательное поле"),
        question: Yup.string().required("Обязательное поле"),
    })

    return (
        <div>
            <h3>Product question</h3>
            <hr/>
            <h5>Will you be getting more stock?</h5>
            <p>You can use any of the payment methods listed below: Visa, MasterCard, American Express, PayPal, Money
               order, Check (educational institutions and governmental bodies only)</p>
            <Formik
                initialValues={initialFormValue}
                onSubmit={null}
                validationSchema={categoryFormValidationSchema}
            >
                {({errors, isValid, setFieldValue, values}) => {
                    return (
                        <Form encType='multipart/form-data'>
                            <FormInput error={Boolean(errors.name)} errorMessage={errors.name} name="name"
                                       label="Название"/>
                            <FormInput error={Boolean(errors.email)} errorMessage={errors.email} name="description"
                                       label="Описание"/>
                            <FormInput error={Boolean(errors.phone)} errorMessage={errors.phone} name="price"
                                       label="Цена"/>
                            <FormInput error={Boolean(errors.question)} errorMessage={errors.question} name="quantity"
                                       label="Количество"/>
                            <button type='submit'></button>
                        </Form>
                    );
                }
                }
            </Formik>
        </div>
    )
}

export default YourOrder