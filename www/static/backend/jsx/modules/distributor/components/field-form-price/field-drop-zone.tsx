import React from "react";
import Dropzone from "react-dropzone";
import {Box, Button, Grid, Typography} from "@material-ui/core";

export const DropZoneFileForm: React.FC<any> = ({onChange, value}) => {

    return (<Dropzone maxFiles={1}
                      onDrop={(fileInfo) => onChange(fileInfo[0])}
                      accept='.xlsx, .xls'
                      name='image'
                      noDrag={true}
    >
        {({getRootProps, getInputProps}) => (
            <section>
                <div className='drop-zone' {...getRootProps()}>
                    <input {...getInputProps()}/>
                    <Box width='100%'>
                        <Box mt={1}>
                            <Grid container justify='center'>
                                <label htmlFor="contained-button-file">
                                    <Button style={{backgroundColor: '#ffb400'}} variant="contained" color="primary" component="span">
                                        Select file
                                    </Button>
                                </label>
                            </Grid>
                        </Box>
                    </Box>
                </div>
            </section>
        )}
    </Dropzone>)
}