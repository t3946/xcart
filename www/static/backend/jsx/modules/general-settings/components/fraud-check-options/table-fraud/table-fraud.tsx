import React from "react";
interface ITableFraud {
  columns: string[];
}
export const TableFraud: React.FC<ITableFraud> = ({ columns }) => {
  return (
    <table>
      <tr>
        {columns.map((column) => (
          <th>{column}</th>
        ))}
      </tr>
    </table>
  );
};
