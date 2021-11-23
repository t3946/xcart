import React from "react";
import {
  FormControl,
  InputLabel,
  MenuItem,
  Select,
  Stack,
} from "@mui/material";
import { Site } from "@admin/modules/distributor/ts/types/table-price.types";
import { Typography } from "@material-ui/core";
interface StorefrontSelect {
  sites: Site[];
  handleChange: (e: React.ChangeEvent<HTMLSelectElement>) => void;
  storefront: string;
}
export const StorefrontSelect: React.FC<StorefrontSelect> = ({
  sites,
  handleChange,
  storefront,
}) => {
  return (
    <Stack alignItems="center" justifyContent="center">
      <Typography variant="h6" align="center">
        Select storefront
      </Typography>
      <select value={storefront} onChange={(e) => handleChange(e)}>
        {sites.map((site) => (
          <option value={site.storefrontid}>{site.domain}</option>
        ))}
      </select>
    </Stack>
  );
};
