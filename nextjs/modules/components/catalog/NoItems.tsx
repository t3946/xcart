import * as React from "react";
import NoItemsBase from "@modules/account/components/common/NoItems";
import Button from "@modules/ui/forms/Button";

export const NoItems: React.FC = function () {
  return (
    <NoItemsBase message={"This list is empty"}>
      <a href="/" className={"text-decoration-none"}>
        <Button className={"w-md-auto mt-2 mt-md-3 mt-2"}>Shop now</Button>
      </a>
    </NoItemsBase>
  );
};

export default NoItems;
