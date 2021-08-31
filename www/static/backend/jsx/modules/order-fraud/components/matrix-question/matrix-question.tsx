import React, { useContext } from "react";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";
import { FAAnswer } from "@admin/modules/order-fraud/ts/types/answer";
import { getHeaderClassByName } from "@admin/modules/order-fraud/utils/add-color-column";
interface MatrixQuestion {
  columns: {
    fraud_code: string;
    fraud_name: string;
    type: string;
    fraud_id: string | number;
  }[];
  answerList: FAAnswer[];
}

export const MatrixQuestion: React.FC<MatrixQuestion> = ({
  columns,
  answerList,
}) => {
  const { dialog, template } = useContext(FraudCheckOrderContext);

  const onClickHandler = (anwer: FAAnswer) => {
    template.set(anwer);
    dialog.set();
  };
  return (
    <table border={1} className="table-fa-question">
      <tr>
        <th className="table-header-code-text">code</th>
        {columns.map((column) => (
          <th
            className={`table-header-fraud ${getHeaderClassByName(
              column.fraud_name
            )}`}
          >
            {column.fraud_name}
          </th>
        ))}
      </tr>
      {columns.map((column) => {
        return (
          <tr>
            <td
              className={`table-header-fraud ${getHeaderClassByName(
                column.fraud_name
              )}`}
            >
              {column.fraud_name}
            </td>
            {columns.map((col) => {
              if (col.fraud_id === column.fraud_id) {
                return <td>&#10003;</td>;
              }
              const answer = answerList.find((answer) => {
                return (
                  answer.f_fraud_name === column.fraud_name &&
                  answer.t_fraud_name === col.fraud_name
                );
              });
              if (answer) {
                return (
                  <td>
                    <div
                      onClick={() => onClickHandler(answer)}
                      className="answer-matrix-detail-text"
                    >
                      {answer.fraud_score}
                    </div>
                  </td>
                );
              } else {
                return <td>Redundant</td>;
              }
            })}
          </tr>
        );
      })}
    </table>
  );
};
