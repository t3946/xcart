import React from "react";
import { useRouter } from "next/router";
import { List } from "@modules/account/ts/types/list.type";
import cn from "classnames";
import Button, { ETheme } from "@modules/ui/forms/Button";

import Styles from "@modules/account/components/lists/NoItemsBlock.module.scss";

interface NoItemsBlockProps {
  listInfo: List;
}

export const NoItemsBlock: React.FC<NoItemsBlockProps> = ({ listInfo }) => {
  const router = useRouter();
  const addIdea = () => {
    router.push(`/shopping-lists/action-list/add-idea/${listInfo.cacheUrl}`);
  };

  return (
    <div className={cn(Styles.container, "no-items-block-container")}>
      <img
        className={Styles.image}
        src="/static/frontend/images/icons/account/no-items.svg"
      />

      <div className="mt-12 mb-16">
        There are no items in this List. Add items you want to shop for.
      </div>

      <Button
        theme={ETheme.outlined}
        onClick={addIdea}
        type={"submit"}
        className="full-width d-lg-none"
      >
        Add idea to list
      </Button>
    </div>
  );
};
