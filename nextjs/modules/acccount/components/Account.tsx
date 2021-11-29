import * as React from "react";
import Counter from "@modules/acccount/components/Counter";

export interface IUser {
  userId: number;
  name: string;
}

interface IProps {
  user: IUser;
}

const Account: React.FC<IProps> = function (props: IProps) {
  return (
    <div>
      <h1>Hello! This is {props.user.name}'s account page</h1>
      <Counter />
    </div>
  );
};

export default Account;
