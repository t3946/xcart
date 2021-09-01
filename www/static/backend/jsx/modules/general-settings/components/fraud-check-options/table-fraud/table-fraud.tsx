import React, { useState, Fragment } from "react";
import { TableDataResponse } from "@admin/modules/general-settings/ts/types/fraud-check/question-data.type";
import { ChangeQuestionDataForm } from "@admin/modules/general-settings/ts/types/fraud-check/data";
import { DialogTableEdit } from "@admin/modules/general-settings/components/fraud-check-options/dialog-table-edit/DialogTableEdit";

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
      <table>
        <tr>
          <th className="table-header-fraud-empty">code</th>
          {columns.map((column) => (
            <th className="table-header-fraud">{column}</th>
          ))}
        </tr>
        {columns.map((column) => {
          return (
            <tr>
              <td className="table-header-fraud">{column}</td>
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
                    <td onDoubleClick={() => onClickChangeQuestion(question)}>
                      {question.value}
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
