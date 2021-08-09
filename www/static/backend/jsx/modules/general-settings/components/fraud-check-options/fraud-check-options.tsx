import React, { useEffect, useState } from "react";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { Container, Grid } from "@material-ui/core";
import { TableFraud } from "@admin/modules/general-settings/components/fraud-check-options/table-fraud/table-fraud";

const api = new ApiService();

interface IFrauds {
  address: [];
  full_name: [];
  status: boolean;
}

export const FraudCheckOptions: React.FC<any> = () => {
  const [frauds, setFrauds] = useState<IFrauds>({});

  useEffect(() => {
    api.get("/api/fraud/get/all").then((res) => {
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
  console.log(frauds);
  return (
    <Container>
      <Grid
        container
        justifyContent="center"
        alignItems="center"
        direction="column"
      >
        <TableFraud columns={}></TableFraud>
      </Grid>
    </Container>
  );
};
