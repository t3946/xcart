import React from "react";
import { ShareList } from "@modules/account/components/lists/ShareList";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import UseSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";

export const ShareListPage: React.FC = () => {
  const router = useRouter();
  const { cache } = router.query;
  const lists = UseSelectorAccount((state) => state.lists.lists);

  const list = lists.find((e) => e.cacheUrl === cache);

  const onCancelClick = () => {
    router.push(`/shopping-lists/${list.cacheUrl}`);
  };

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/account/your-lists/${list.cacheUrl}`}
        label={"back"}
      />
      <div className="page-label">Share list with others</div>
      <ShareList onClose={onCancelClick} cache={cache} />
    </div>
  );
};
