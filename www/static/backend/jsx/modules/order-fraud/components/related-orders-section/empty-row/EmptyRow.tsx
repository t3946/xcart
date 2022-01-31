import React from "react";
interface EmptyRow {
  label?: string;
}
export const EmptyRow: React.FC<EmptyRow> = ({ label }) => {
  return (
    <tr className={!label ? "empty-row" : ""}>
      {label ? (
        <td>
          <b>{label}</b>
        </td>
      ) : (
        <td />
      )}
      <td />
      <td />
      <td />
    </tr>
  );
};
