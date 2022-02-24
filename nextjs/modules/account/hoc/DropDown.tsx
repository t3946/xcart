import React from "react";
import { Dropdown } from "react-bootstrap";
import TransitionFade from "@modules/account/components/shared/TransitionFade";
import useClickListener from "@modules/account/hooks/useClickListener";
import classnames from "classnames";

interface IProps {
  toggle: (isOpen: boolean) => any;
  items: any;
  onSelect?: (eventKey: string) => any;
  active?: string;
  classes?: {
    dropDown?: any;
    menu?: any;
    item?: any;
  };
}

const Navigation: React.FC<IProps> = (props: IProps) => {
  const [isOpen, setIsOpen] = React.useState(false);
  const { toggle, items, onSelect, active } = props;
  const outSideClickListener = useClickListener(setIsOpen);

  const classes = {
    menu: ["position-absolute", props.classes?.menu],
    item: ["d-flex", "align-items-center", props.classes?.item],
  };

  outSideClickListener.startListen();

  const CustomToggle = React.forwardRef((props: Record<any, any>, ref: any) => {
    const { onClick } = props;

    return (
      <div
        onClick={(e) => {
          e.stopPropagation();
          onClick(e);
        }}
        ref={ref}
      >
        {toggle(isOpen)}
      </div>
    );
  });

  const CustomMenu = React.forwardRef((children, ref: any) => {
    const dropDownItems = items.map((value, i) => {
      return (
        <Dropdown.Item
          eventKey={i}
          active={i.toString() === active}
          className={classnames(classes.item)}
        >
          {value}
        </Dropdown.Item>
      );
    });

    return (
      <div
        ref={ref}
        onClick={() => {
          setIsOpen(false);
        }}
        className={classnames(classes.menu)}
      >
        {dropDownItems}
      </div>
    );
  });

  React.useEffect(() => {
    return () => {
      outSideClickListener.endListen();
    };
  });

  return (
    <Dropdown
      onToggle={(isOpen) => {
        setIsOpen(isOpen);
      }}
      onSelect={(eventKey) => {
        onSelect && onSelect(eventKey);
      }}
      onClick={(e) => e.stopPropagation()}
      className={classnames("position-relative", props.classes?.dropDown)}
    >
      <Dropdown.Toggle id="dropdown-basic" as={CustomToggle} />

      <TransitionFade show={isOpen}>
        <Dropdown.Menu as={CustomMenu} />
      </TransitionFade>
    </Dropdown>
  );
};

export default Navigation;
