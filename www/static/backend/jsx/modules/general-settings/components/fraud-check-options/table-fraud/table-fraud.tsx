import React, { useState, Fragment } from "react";
import { TableDataResponse } from "@admin/modules/general-settings/ts/types/fraud-check/question-data.type";
import { ChangeQuestionDataForm } from "@admin/modules/general-settings/ts/types/fraud-check/data";
import { DialogTableEdit } from "@admin/modules/general-settings/components/fraud-check-options/dialog-table-edit/DialogTableEdit";
import { getHeaderClassByName } from "@admin/modules/order-fraud/utils/add-color-column";

interface ITableFraud {
  columns: string[];
  data: TableDataResponse[];
  type: string;
}
export const TableFraud: React.FC<ITableFraud> = ({ columns, data, type }) => {
  const [dataChange, setDataChange] = useState<ChangeQuestionDataForm>(null);
  const [open, setOpen] = useState(false);
  const onClickChangeQuestion = (question: TableDataResponse) => {
    setDataChange({
      weight: question.value,
      template: question.template,
      questionId: question.questionId,
      type,
    });
    setOpen(true);
  };
  return (
    <Fragment>
      <table border={1} className="table-question-fraud-check">
        <tr>
          <th className="table-header-fraud-empty">code</th>
          {columns.map((column) => (
            <th
              className={`table-header-fraud ${getHeaderClassByName(column)}`}
            >
              {column}
            </th>
          ))}
        </tr>
        {columns.map((column) => {
          return (
            <tr>
              <td
                className={`table-header-fraud ${getHeaderClassByName(column)}`}
              >
                {column}
              </td>
              {columns.map((col) => {
                if (col === column) {
                  return <td>&#10003;</td>;
                }
                const question = data.find(
                  (answer) =>
                    answer.f_fraud === column && answer.t_fraud === col
                );
                if (question) {
                  return (
                    <td>
                      <div
                        className="question-matrix-detail-text"
                        onClick={() => onClickChangeQuestion(question)}
                      >
                        {question.value}
                      </div>
                    </td>
                  );
                }
                return <td>Redundant</td>;
              })}
            </tr>
          );
        })}
      </table>
      <DialogTableEdit
        type="faQuestions"
        state={{ get: open, set: setOpen }}
        form={{ get: dataChange, set: setDataChange }}
      />
    </Fragment>
  );
};
