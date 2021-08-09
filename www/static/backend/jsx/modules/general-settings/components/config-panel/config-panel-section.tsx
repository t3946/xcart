import React from 'react';
import {Grid} from "@material-ui/core";
import { Link } from "react-router-dom";

interface IConfigPanelSection {
    arItems: {
        lang: string,
        isNew: boolean,
        name: string,
        url: string|boolean
    }[]
}

export const ConfigPanelSection: React.FC<IConfigPanelSection> = ({arItems}) => {
    return (
        <div className='section-config-block'>
            <Grid
                container
                direction="column"
                justifyContent="center"
                alignItems="center"
                style={{width: 'auto'}}
            >
                {
                    arItems.map(item => {
                        return (<div className='section-item-block'>
                            {!item.isNew
                                ? (<a href={!item.url ? `configuration.php?option=${item.name}` : item.url} className='section-item-link'>{item.lang}</a>)
                                : (<Link to={!item.url ? `/admin/list/Core/GeneralSettingsAdmin/${item.name}` : item.url}>{item.lang}</Link>)
                            }
                        </div>)
                    })
                }
            </Grid>
        </div>
    )
}