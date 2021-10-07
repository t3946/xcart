import React from 'react';
import {Form} from 'react-bootstrap';

export const SelectField: React.FC<any> = ({options, onChange, index, valueList, indexTable}) => {
    return (
        <Form.Group controlId={index}>
            <Form.Control data-index-table={indexTable} onChange={onChange} as="select">
                {options.map(select => (
                    <option selected={valueList[indexTable] && valueList[indexTable][index]===select}
                            value={select}>{select}
                    </option>)
                )}
            </Form.Control>
        </Form.Group>
    )
}