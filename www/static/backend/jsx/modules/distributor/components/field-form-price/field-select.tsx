import React from 'react';
import {Form} from 'react-bootstrap';

export const SelectField: React.FC<any> = ({options, onChange, index, valueList}) => {

    return (
        <Form.Group controlId={index}>
            <Form.Control onChange={onChange} as="select">
                {options.map(select => (
                    <option selected={valueList[index]===select}
                            value={select}>{select}
                    </option>)
                )}
            </Form.Control>
        </Form.Group>
    )
}