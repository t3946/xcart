import React, { useState, Fragment } from "react";
import { useSelector } from "react-redux";
import { StoreGeneralSettings } from "@admin/modules/general-settings/ts/types/general-settings/generalSettings.type";
import EditIcon from "@material-ui/icons/Edit";
import { DialogTableEdit } from "@admin/modules/general-settings/components/fraud-check-options/dialog-table-edit/DialogTableEdit";
import { ChangeQuestionDataForm } from "@admin/modules/general-settings/ts/types/fraud-check/data";
import { TableBaseQuestion } from "@admin/modules/general-settings/ts/types/fraud-check/question-data.type";

export const BaseQuestionTable: React.FC = () => {
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState<ChangeQuestionDataForm>(null);
  const baseQuestion = useSelector(
    (state: StoreGeneralSettings) => state.fraudSettings.baseQuestions
  );
  const onClickEditHandler = (question: TableBaseQuestion) => {
    setForm({
      questionId: question.questionId,
      weight: question.weight,
      template: question.template,
      type: question.type,
    });
    setOpen(!open);
  };
  return (
    <Fragment>
      <table border="1">
        <tr>
          <th>Question code</th>
          <th>Auto</th>
          <th>Weight</th>
          <th>Type</th>
          <th>Edit</th>
        </tr>
        {baseQuestion.map((question) => (
          <tr>
            <td className="base-question-table-item-code">
              {question.questionCode}
            </td>
            <td>{question.auto}</td>
            <td>{question.weight}</td>
            <td>{question.type}</td>
            <td>
              <EditIcon onClick={() => onClickEditHandler(question)} />
            </td>
          </tr>
        ))}
      </table>
      <DialogTableEdit
        state={{ get: open, set: setOpen }}
        form={{ get: form, set: setForm }}
        type="baseQuestions"
      />
    </Fragment>
  );
};
