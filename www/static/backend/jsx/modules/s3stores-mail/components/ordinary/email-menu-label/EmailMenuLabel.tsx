import React, { useContext, useEffect, useState } from "react";
import Checkbox from "@material-ui/core/Checkbox";
import FormGroup from "@material-ui/core/FormGroup";
import FormControlLabel from "@material-ui/core/FormControlLabel";
import { EmailLabel } from "@s3stores-mail/ts/types/email.type";
import { Grid } from "@material-ui/core";
import { EmailLabelContext } from "@s3stores-mail/contexts/email-label-context/EmailLabelContext";
import { addLabelEmail, createLabel, removeLabelEmail } from "@redux/actions";
import { useDispatch } from "react-redux";
import { EmailThreadContext } from "@s3stores-mail/contexts/email-thread-context/EmailThread.context";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";

interface EmailMenuLabel {
  labelList: EmailLabel[];
  labelMailList: EmailLabel[];
}

export const EmailMenuLabel: React.FC<EmailMenuLabel> = ({
  labelList,
  labelMailList,
}) => {
  const [search, setSearch] = useState("");
  const [searchLabel, setSearchLabel] = useState([]);
  const dispatch = useDispatch();
  const { modal, messageId } = useContext(EmailLabelContext);
  const { emailInfo } = useContext(EmailThreadContext);
  const { onAddLabel, onDeleteLabel } = useContext(EmailInfoContext);
  const onSearch = (event: React.ChangeEvent<HTMLInputElement>) => {
    setSearch(event.target.value);
    setSearchLabel(() => {
      const labels = [];
      for (const label of labelList) {
        if (labels.length < 5) {
          if (label.name.indexOf(event.target.value) !== -1) {
            labels.push(label);
          }
        }
      }
      return labels;
    });
  };
  useEffect(() => {
    if (!searchLabel.length && search === "") {
      setSearchLabel(labelList.slice(0, 5));
    }
  });
  const onSelectLabel = (event) => {
    if (event.target.checked) {
      onAddLabel(emailInfo, event.target.id);
    } else {
      onDeleteLabel(emailInfo, event.target.id);
    }
  };

  return (
    <div className="mail-menu-labels">
      <div className="title-menu-labels">Assign label:</div>
      <div className="mail-menu-search-block">
        <input
          className="search-input-label"
          value={search}
          onChange={onSearch}
        />
      </div>
      <div className="mail-menu-list-label">
        <FormGroup aria-label="position" row>
          <Grid container direction="column">
            {searchLabel &&
              searchLabel.map((label: EmailLabel) => {
                const isSelect = labelMailList.find(
                  (lb) => lb.label_id === label.label_id
                );
                return (
                  <FormControlLabel
                    value={label.name}
                    control={
                      <Checkbox
                        checked={isSelect != null}
                        onChange={onSelectLabel}
                        color="primary"
                        id={label.label_id}
                      />
                    }
                    label={label.name}
                  />
                );
              })}
          </Grid>
        </FormGroup>
      </div>
      <div className="mail-menu-line-label" />
      <div className="mail-menu-create-label-title" onClick={modal.set}>
        Create label
      </div>
    </div>
  );
};
