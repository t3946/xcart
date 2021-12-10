import React from "react";
import Accordion from "@mui/material/Accordion";
import AccordionSummary from "@mui/material/AccordionSummary";
import AccordionDetails from "@mui/material/AccordionDetails";
import Typography from "@mui/material/Typography";
import ExpandMoreIcon from "@mui/icons-material/ExpandMore";
import { UploadLog } from "@admin/modules/distributor/ts/types/table-price.types";
import moment from "moment";
import Divider from "@mui/material/Divider";
import Box from "@mui/material/Box";
interface UploadLogs {
  logs: UploadLog[];
}
export const UploadLogs: React.FC<UploadLogs> = ({ logs }) => {
  return (
    <Box my={2}>
      <Typography mb={1} variant="h5" align="center">
        Upload logs
      </Typography>
      <Divider />
      {logs.map((log) => (
        <Accordion disabled={log.status === "In process"} key={log.uploadId}>
          <AccordionSummary expandIcon={<ExpandMoreIcon />}>
            <Typography>{`Upload from ${moment(log.date).format("lll")} | ${
              log.name
            } | ${log.status}`}</Typography>
          </AccordionSummary>
          <AccordionDetails>
            <Typography variant="body2">
              Upload file user: {log.userUpload}
            </Typography>
            <Typography variant="body2">
              Total rows updated/created: {log.count}
            </Typography>
            <Typography variant="body2">Status: {log.status}</Typography>
          </AccordionDetails>
        </Accordion>
      ))}
    </Box>
  );
};
