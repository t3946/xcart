import React from "react";
import { Button } from "@material-ui/core";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import { useHistory } from "react-router-dom";
import { List } from "@client/modules/account/ts/types/list.type";

interface NoItemsBlockProps {
  listInfo: List;
}

export const NoItemsBlock: React.FC<NoItemsBlockProps> = ({ listInfo }) => {
  const breakPoint = useBreakpoint();

  const history = useHistory();

  const addIdea = () => {
    history.push(`/account/your-lists/add-idea/${listInfo.cache_url}`);
  };

  return (
    <div className="no-items-block-container">
      <img
        className="no-items-block-img"
        src="/static/frontend/images/icons/account/no-items.svg"
      />
      <div className={"no-items-block-text"}>
        There are no items in this List. Add items you want to shop for.
      </div>
      {breakPoint({
        xs: (
          <Button
            onClick={addIdea}
            type={"submit"}
            className="account-submit-btn account-submit-btn-outline full-width"
          >
            Add idea to list
          </Button>
        ),
        md: null,
      })}
    </div>
  );
};
