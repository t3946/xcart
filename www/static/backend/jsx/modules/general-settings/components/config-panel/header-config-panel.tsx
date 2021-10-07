import React from 'react'
import ArrowDropUpOutlinedIcon from "@material-ui/icons/ArrowDropUpOutlined";
import ArrowDropDownOutlinedIcon from "@material-ui/icons/ArrowDropDownOutlined";

interface IHeaderConfigPanel {
    collapseState: { get: boolean, set: void }
}

export const HeaderConfigPanel: React.FC<IHeaderConfigPanel> = ({collapseState}) => {
    return (
        <div onClick={collapseState.set} className='up-config-panel'>
            <div className='config-header-title'>General settings panel</div>
            <div className='icon-config-collapse-block'>
                {collapseState.get
                    ? <ArrowDropUpOutlinedIcon/>
                    : <ArrowDropDownOutlinedIcon/>}
            </div>
        </div>
    )
}