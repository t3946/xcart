import React from "react";
import { Box } from "@mui/material";
import { initialMatchingAddress } from "@admin/modules/order-fraud/ts/consts/initial";

export const MatchingAddress: React.FC = () => {
  return (
    <table className="matching-table">
      <tr className="history-header">
        <th>
          Matching
          <br />
          degrees
        </th>
        <th>Two addresses have</th>
      </tr>
      {initialMatchingAddress.map((item) => (
        <tr>
          <td className="value">{item.value}</td>
          <td className="description">{item.description}</td>
        </tr>
      ))}
    </table>
  );
};
