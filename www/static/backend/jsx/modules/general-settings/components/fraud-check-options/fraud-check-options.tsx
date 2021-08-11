import React, { useEffect, useState } from "react";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { Container, Grid, Typography } from "@material-ui/core";
import { TableFraud } from "@admin/modules/general-settings/components/fraud-check-options/table-fraud/table-fraud";
import { CheckSettingsForm } from "@admin/modules/general-settings/components/fraud-check-options/check-settings-form/check-settings-form";

const api = new ApiService();

interface IFrauds {
  address?: { data: []; columns: string[]; data_section: [] };
  full_name?: { data: []; columns: string[]; data_section: [] };
}

interface IResponse extends IFrauds {
  status: boolean;
}

export const FraudCheckOptions: React.FC<any> = () => {
  const [frauds, setFrauds] = useState<IFrauds>({});

  useEffect(() => {
    api.get("/api/fraud/get/all").then((res: IResponse) => {
      if (res.status) {
        setFrauds((prevState) => ({
          ...prevState,
          ...{
            full_name: res.full_name ?? [],
            address: res.address ?? [],
          },
        }));
      }
    });
  }, []);

  return (
    <Container>
      <Grid
        container
        justifyContent="center"
        alignItems="center"
        direction="column"
      >
        <CheckSettingsForm />
        <Typography variant="h6" align="center">
          Table full name fraud
        </Typography>
        <div className="frauds__full_name_table">
          {frauds.full_name && (
            <TableFraud
              data={frauds.full_name.data}
              columns={frauds.full_name?.columns}
              section={frauds.full_name.data_section}
            />
          )}
        </div>
        <Typography variant="h6" align="center">
          Table address fraud
        </Typography>
        {frauds.address && (
          <TableFraud
            columns={frauds.address.columns}
            data={frauds.address.data}
            section={frauds.address.data_section}
          />
        )}
      </Grid>
    </Container>
  );
};
