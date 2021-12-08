import React, { useState, Fragment } from "react";
import { useSelector } from "react-redux";
import { StoreGeneralSettings } from "@admin/modules/general-settings/ts/types/general-settings/generalSettings.type";
import EditIcon from "@material-ui/icons/Edit";
import { DialogTableEdit } from "@admin/modules/general-settings/components/fraud-check-options/dialog-table-edit/DialogTableEdit";
import { ChangeQuestionDataForm } from "@admin/modules/general-settings/ts/types/fraud-check/data";
import { TableBaseQuestion } from "@admin/modules/general-settings/ts/types/fraud-check/question-data.type";

export const TableBaseQuestions: React.FC = () => {
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState<ChangeQuestionDataForm>(null);
  const baseQuestion = useSelector(
    (state: StoreGeneralSettings) => state.fraudSettings.baseQuestions
  );
  const onClickEditHandler = (question: TableBaseQuestion) => {
    setForm({
      questionCode: question.questionCode,
      questionId: question.questionId,
      weight: question.weight,
      template: question.template,
      type: question.type,
      orderBy: question.orderBy,
    });
    setOpen(!open);
  };
  return (
    <Fragment>
      <table border="1">
        <tr>
          <th>Question code</th>
          <th>Question template</th>
          <th>Auto</th>
          <th>Question weight</th>
          <th>Question type Type</th>
          <th>Order by</th>
          <th>Edit</th>
        </tr>
        {baseQuestion.map((question) => (
          <tr>
            <td className="base-question-table-item-code">
              {question.questionCode}
            </td>
            <td>{question.template}</td>
            <td>{question.auto}</td>
            <td>{question.weight}</td>
            <td>{question.type}</td>
            <td>{question.orderBy}</td>
            <td style={{ cursor: "pointer" }}>
              <EditIcon onClick={() => onClickEditHandler(question)} />
            </td>
          </tr>
        ))}
      </table>
      {open && (
        <DialogTableEdit
          template={form}
          onClose={() => setOpen(false)}
          onChangeTemplate={setForm}
          type="baseQuestions"
          isBase
          open={open}
        />
      )}
    </Fragment>
  );
};
