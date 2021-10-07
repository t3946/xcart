import React from 'react';

export default function LoadingDialog() {
    return (
        <div style={{textAlign: 'center'}}>
            <div className="lds-ellipsis">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>)
}