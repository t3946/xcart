import React from "react";
import { FAAnswer } from "@admin/modules/order-fraud/ts/types/answer";
import { getHeaderClassByName } from "@admin/modules/order-fraud/utils/add-color-column";
import { convertResult } from "@admin/modules/order-fraud/utils/convert-fraud-score";
interface MatrixQuestion {
  columns: {
    fraud_code: string;
    fraud_name: string;
    type: string;
    fraud_id: string | number;
  }[];
  answerList: FAAnswer[];
  handleClickAnswer: (
    event: React.MouseEvent<HTMLDivElement>,
    answer: FAAnswer
  ) => void;
}

export const MatrixQuestion: React.FC<MatrixQuestion> = ({
  columns,
  answerList,
  handleClickAnswer,
}) => {
  return (
    <table border={1} className="table-question-fraud-check">
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
              className={`table-header-fraud column-name ${getHeaderClassByName(
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
                      onClick={(e) => handleClickAnswer(e, answer)}
                      className="answer-matrix-detail-text"
                    >
                      {convertResult(answer)}
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
