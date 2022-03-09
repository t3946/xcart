import * as React from "react";
import Link from "next/link";
import { LinkProps } from "next/dist/client/link";
import { useRouter } from "next/router";

interface IProps extends LinkProps {
  children: (isActive: boolean) => React.ReactElement;
}

const ActiveLink: React.FC<IProps> = function (props: IProps) {
  const router = useRouter();
  const isActive = props.href === router.asPath;

  if (isActive) {
    return props.children(isActive);
  } else {
    return <Link {...props}>{props.children(isActive)}</Link>;
  }
};

export default ActiveLink;
