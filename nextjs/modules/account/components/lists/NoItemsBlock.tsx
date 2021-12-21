import React from "react";
import { useRouter } from "next/router";
import { List } from "@modules/account/ts/types/list.type";

interface NoItemsBlockProps {
  listInfo: List;
}

export const NoItemsBlock: React.FC<NoItemsBlockProps> = ({ listInfo }) => {
  const router = useRouter();
  const addIdea = () => {
    router.push(`/your-lists/add-idea/${listInfo.cache_url}`);
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

      <button
        onClick={addIdea}
        type={"submit"}
        className="form-button account-submit-btn account-submit-btn-outline full-width d-md-none"
      >
        Add idea to list
      </button>
    </div>
  );
};
