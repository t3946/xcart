import React, { useContext } from "react";
import { FraudQuestion } from "@admin/modules/order-fraud/ts/types/settings";
import { Grid } from "@material-ui/core";
import { FraudRadioField } from "@admin/modules/order-fraud/components/fraud-table-question/fraud-radio-field";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";
import { MainAnswer } from "@admin/modules/order-fraud/ts/types/answer";
interface FraudTableQuestion {
  listAnswer: MainAnswer[];
  nameTable: string;
}
export const FraudTableQuestion: React.FC<FraudTableQuestion> = ({
  listAnswer,
  nameTable,
}) => {
  return (
    <table>
      <tr className="table-head__fraud-check-question">
        <th>FC question</th>
        <th>Auto/Manual</th>
        <th>Outcome</th>
        <th>Weight</th>
        <th>Fraud score</th>
      </tr>
      {listAnswer &&
        listAnswer.map((answer) => {
          return (
            <tr
              className={`table-item-${answer.fraud_result}__fraud-check-question`}
            >
              <td>
                <Grid container justifyContent="center" direction="column">
                  <div className="question-code-title">
                    Question code: {answer.question_code}
                  </div>
                  <div
                    dangerouslySetInnerHTML={{
                      __html: answer.template,
                    }}
                  />
                </Grid>
              </td>
              <td>
                {answer.question_auto === "Y" && !answer.manual_action ? (
                  "Auto"
                ) : (
                  <FraudRadioField
                    section={nameTable}
                    fraudCode={answer.question_code}
                  />
                )}
              </td>
              <td>{answer.fraud_result === "positive" ? 1 : 0}</td>
              <td>{answer.question_weight}</td>
              <td>{answer.fraud_score}</td>
            </tr>
          );
        })}
    </table>
  );
};
