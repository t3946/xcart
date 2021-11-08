import React, { useEffect, useState } from "react";
import { useSelector } from "react-redux";
import { ListHeader } from "@client/modules/account/components/lists/ListHeader";
import { Button } from "@material-ui/core";
import { ListProductItems } from "@client/modules/account/components/lists/ListProductItems";
import { useHistory, useParams } from "react-router-dom";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import { Sceleton } from "@client/modules/shared/components/sceleton/Sceleton";
import { ListProductItemSkeleton } from "../components/lists/ListProductItemSkeleton";
import { UserPrivateVariantsEnum } from "@client/modules/account/ts/consts/user-private-variants.enum";
import { AddIdea } from "@client/modules/account/components/lists/AddIdea";
import { useDialog } from "@client/modules/account/hooks/useDialog";
import BootstrapDialogHOC from "@client/modules/account/hoc/BootstrapDialogHOC";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import { ListMobileMenu } from "@client/modules/account/components/lists/ListMobileMenu";
import { List } from "@client/modules/account/ts/types/list.type";
import { MobileMenuBackBtn } from "@client/modules/account/pages/MobileMenuBackBtn";

export const ListsPage: React.FC = () => {
  const { id }: { id: string } = useParams();

  const lists = useSelector((e: StoreInterface) => e.lists.lists);

  const [list, setList] = useState<List | null>(null);

  const createIdeaDialog = useDialog();

  const breakpoints = useBreakpoint();

  const history = useHistory();

  const edit = list?.list_info.role !== UserPrivateVariantsEnum.VIEW;

  const viewLists = () => {
    return (
      <React.Fragment>
        <ListHeader
          shippingList={!!id}
          label={list?.name}
          edit={edit}
          info={list}
        />
        <ListProductItems edit={edit} path={history.location} info={list} />
      </React.Fragment>
    );
  };

  useEffect(() => {
    if (lists && id) {
      setList(lists.find((e) => e.cache_url === id));
    } else if (lists && !id) {
      setList(lists[0]);
    }
  }, [lists]);

  return (
    <div>
      {id && (
        <MobileMenuBackBtn
          redirectUrl={`/account/your-lists/`}
          label={"account"}
        />
      )}
      {!!list ? (
        breakpoints({
          xs: id ? viewLists() : <ListMobileMenu lists={lists} />,
          lg: viewLists(),
        })
      ) : (
        <React.Fragment>
          <div className="list-header-container">
            <Sceleton height={36} maxWidth={"100%"} />
          </div>

          {Array.from({ length: 3 }, (v, k) => k).map(() => {
            return <ListProductItemSkeleton />;
          })}
        </React.Fragment>
      )}
      <BootstrapDialogHOC
        show={createIdeaDialog.open}
        title={"Create a new idea"}
        onClose={createIdeaDialog.handleClose}
      >
        <AddIdea
          listHash={id || list?.cache_url}
          onCancelBtnClick={createIdeaDialog.handleClose}
        />
      </BootstrapDialogHOC>
      {breakpoints({
        xs: (
          <Button
            onClick={createIdeaDialog.handleClickOpen}
            type={"submit"}
            disabled={!lists || !edit}
            className="account-submit-btn account-submit-btn-outline add-idea-btn"
          >
            Add idea to list
          </Button>
        ),
      })}
    </div>
  );
};
