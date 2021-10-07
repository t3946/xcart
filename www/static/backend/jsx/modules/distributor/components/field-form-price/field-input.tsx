import React from 'react';

export const InputFieldForm: React.FC<any> = ({value}) => {
    return (
        <input type='text' value={value} className='dx__form-input-text'/>
    );
}