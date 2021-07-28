import React from 'react';
import {makeStyles, Theme} from '@material-ui/core/styles';
import AppBar from '@material-ui/core/AppBar';
import Tab from '@material-ui/core/Tab';
import TabContext from '@material-ui/lab/TabContext';
import TabList from '@material-ui/lab/TabList';
import TabPanel from '@material-ui/lab/TabPanel';
import {TablePrice} from "@admin/modules/distributor/components/table-price/table-price";

const useStyles = makeStyles((theme: Theme) => ({
    root: {
        flexGrow: 1,
        backgroundColor: theme.palette.background.paper,
    },
    bar: {
        background: '#ffb400'
    }
}));

export const TabsTable = ({arTable, select, arTableName}) => {
    const classes = useStyles();
    const [value, setValue] = React.useState('1');

    const handleChange = (event: React.ChangeEvent<{}>, newValue: string) => {
        setValue(newValue);
    };

    return (
        <div className={classes.root}>
            <TabContext value={value}>
                <AppBar position="static" className={classes.bar}>
                    <TabList centered variant='fullWidth' onChange={handleChange} aria-label="simple tabs example">
                        {
                            arTable.map((table, i) => (<Tab label={arTableName[i]} value={`${i + 1}`}/>))
                        }
                    </TabList>
                </AppBar>
                {
                    arTable.map((table, i) => {
                        return (<TabPanel value={`${i + 1}`}>
                            <TablePrice arTable={arTable[i]} select={select}/>
                        </TabPanel>)
                    })
                }
            </TabContext>
        </div>
    );
}
