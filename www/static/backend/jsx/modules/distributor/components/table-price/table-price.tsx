import React, {useEffect, useRef, useState} from 'react';
import {selectColumn} from "@admin/modules/distributor/components/table-price/constants";
import {SelectField} from "@admin/modules/distributor/components/field-form-price/field-select";

export function TablePrice({arTable, select}) {
    const [resizable, setResizable] = useState(true);
    const [resizer, setResizer] = useState(null);
    const tableRef = useRef(null);

    useEffect(() => {
        if (resizable) {
            enableResize();
        }
        return () => {
            if (resizable) {
                disableResize();
            }
        }
    }, [])

    const enableResize = () => {
        if (!resizer) {
            const ColumnResizer = require('column-resizer');
            setResizer(new ColumnResizer.default(tableRef.current, {}));
        } else {
            resizer.reset({});
        }
    }
    const disableResize = () => {
        if (resizer) {
            resizer.reset({disable: true});
        }
    }
    return (
        <table className='table__dx-price' ref={tableRef} id="somethingUnique" cellSpacing="0">
            <tr>
                {arTable[0].map((cell, i) => (<th>
                    <SelectField valueList={select.get} onChange={select.set} index={i} options={selectColumn}/>
                </th>))}
            </tr>
            {
                arTable.map(row => (
                    <tr>
                        {row.map(el => (<td>{el}</td>))}
                    </tr>))
            }
        </table>
    );
}