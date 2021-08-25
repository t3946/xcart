import React, { useContext } from "react";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";
import { FAAnswer } from "@admin/modules/order-fraud/ts/types/answer";
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

  const doubleClickHandler = (anwer: FAAnswer) => {
    template.set(anwer);
    dialog.set();
  };
  return (
    <table>
      <tr>
        <th>code</th>
        {columns.map((column) => (
          <th className="table-header-fraud">{column.fraud_name}</th>
        ))}
      </tr>
      {columns.map((column) => {
        return (
          <tr>
            <td className="table-header-fraud">{column.fraud_name}</td>
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
                      onClick={() => doubleClickHandler(answer)}
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
