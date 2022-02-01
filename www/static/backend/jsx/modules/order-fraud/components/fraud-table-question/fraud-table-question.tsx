import React, { Fragment } from "react";
import { Grid, Typography } from "@mui/material";
import { FraudRadioField } from "@admin/modules/order-fraud/components/fraud-table-question/fraud-radio-field";
import { MainAnswer } from "@admin/modules/order-fraud/ts/types/answer";

interface FraudTableQuestion {
  listAnswer: MainAnswer[];
  title: string;
  colorTitle?: string;
  type?: string;
}

export const FraudTableQuestion: React.FC<FraudTableQuestion> = ({
  listAnswer,
  title,
  colorTitle = "black",
  type,
}) => {
  return (
    <Fragment>
      <Typography color={colorTitle} variant="h6" align="center">
        {title}
      </Typography>
      <table className="table-base-question" border={1}>
        <tr className="table-head__fraud-check-question">
          <th className="header-item-question-code">
            Question
            <br />
            code
          </th>
          <th>Question</th>
          <th className="row-type-question">
            Auto
            <br />
            Manual
          </th>
          <th className="row-outcome">Outcome</th>
          <th>Weight</th>
          <th>Fraud score subtotal</th>
        </tr>
        {listAnswer &&
          listAnswer.map((answer) => {
            return (
              <tr
                className={`table-item-${answer.fraud_result}__fraud-check-question`}
              >
                <td className="question-code-header-item">
                  {answer.question_code}
                </td>
                <td className="question-info-header-item">
                  <Grid container justifyContent="center" direction="column">
                    <div
                      dangerouslySetInnerHTML={{
                        __html: answer.template,
                      }}
                    />
                  </Grid>
                </td>
                <td className="center-header-item">
                  {answer.question_auto === "Y" && !answer.manual_action ? (
                    "Auto"
                  ) : (
                    <FraudRadioField fraudCode={answer.question_code} />
                  )}
                </td>
                <td className="center-header-item">{`${
                  answer.outcome === 1 ? "Yes" : "No"
                } = ${answer.outcome}`}</td>
                <td className="center-header-item">
                  {answer.question_weight > 0
                    ? answer.question_weight
                    : `(${answer.question_weight})`}
                </td>
                <td className="center-header-item">{answer.fraud_score}</td>
              </tr>
            );
          })}
      </table>
    </Fragment>
  );
};
