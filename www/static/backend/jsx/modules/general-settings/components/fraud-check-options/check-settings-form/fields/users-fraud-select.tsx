import React from "react";
import { Col } from "react-bootstrap";
import { MenuItem, Select, Input } from "@material-ui/core";
import Chip from "@material-ui/core/Chip";
import { FraudUsers } from "@admin/modules/general-settings/ts/types/fraud-check/data";

interface UsersFraudSelect {
  userList: FraudUsers[];
  state: { get: string[]; set: (event) => void };
  name: string;
}
export const UsersFraudSelect: React.FC<UsersFraudSelect> = ({
  userList,
  state,
  name,
}) => {
  return (
    <Col sm={10}>
      <Select
        labelId="demo-mutiple-chip-label"
        fullWidth
        multiple
        onChange={state.set}
        value={state.get}
        input={<Input name={name} fullWidth id="select-multiple-chip" />}
        renderValue={(selected) => (
          <div style={{ display: "flex", flexWrap: "wrap" }}>
            {(selected as string[]).map((value) => {
              const user = userList.find((user) => user.id == value) ?? {};
              return (
                <Chip
                  key={value}
                  label={user["firstname"] ?? `user - ${value}`}
                  style={{ margin: 2 }}
                />
              );
            })}
          </div>
        )}
      >
        {userList.map((user) => (
          <MenuItem key={user.id} value={user.id}>
            {user.firstname}
          </MenuItem>
        ))}
      </Select>
    </Col>
  );
};
