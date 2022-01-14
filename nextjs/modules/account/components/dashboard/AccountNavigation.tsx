import React from "react";
import cn from "classnames";
import RectangularButton from "@modules/account/components/common/RectangularButton";
import Styles from "@modules/account/components/dashboard/AccountNavigation.module.scss";
import Link from "next/link";
interface ItemProps {
  img: string;
  title: string;
  background: string;
  caption: string;
  url: string;
}

const NavigationItem: React.FC<ItemProps> = ({
  img,
  title,
  background,
  caption,
  url,
}) => {
  const [hover, setHover] = React.useState(false);
  const imgUrl = `/static/frontend/images/pages/account/dashboard/background/${img}.png`;
  const iconUrl = `/static/frontend/images/pages/account/dashboard/icon/${img}.svg`;
  return (
    <RectangularButton
      classNames={{ container: Styles.card }}
      body={
        <Link href={url}>
          <a
            onMouseEnter={() => setHover(true)}
            onMouseLeave={() => setHover(false)}
            className={cn(Styles.link, "d-flex w-100")}
          >
            <div
              className={cn(
                "d-flex",
                "justify-content-center",
                "align-items-center",
                Styles.cardImageWrapper
              )}
            >
              <img
                className={cn(Styles.cardImageBackground, "position-absolute", {
                  [Styles.cardImageBackground_hover]: hover,
                })}
                src={imgUrl}
              />
              <div
                style={{ backgroundColor: background }}
                className={Styles.cardImageBackground}
              />
              <img
                className={cn(Styles.cardImageIcon, "position-absolute", {
                  [Styles.cardImageIcon_hover]: hover,
                })}
                src={iconUrl}
              />
            </div>
            <div
              className={cn(
                "w-100",
                "d-flex",
                "flex-dir-column",
                "justify-content-center",
                Styles.body
              )}
            >
              <div className={Styles.bodyTitle}>{title}</div>
              <span className={Styles.bodyCaption}>{caption}</span>
            </div>
          </a>
        </Link>
      }
    />
  );
};

const AccountNavigation: React.FC = () => {
  const navigations: {
    img: string;
    background: string;
    title: string;
    caption: string;
    url: string;
  }[] = [
    {
      img: "orders",
      background: "rgba(255, 172, 10, 0.63)",
      title: "Orders",
      caption: "Track, return, or buy things again",
      url: "/dashboard",
    },
    {
      img: "payments",
      background: "rgba(0, 150, 31, 0.58)",
      title: "Payments",
      caption: "Manage payment methods and settings, view all transactions",
      url: "/payments/transactions",
    },
    {
      img: "yourList",
      background: "rgba(0, 103, 172, 0.69)",
      title: "Your lists",
      caption: "View, modify, and share your lists, or create new ones",
      url: "/shopping-lists",
    },
    {
      img: "addresses",
      background: "rgba(43, 12, 14, 0.56)",
      title: "Addresses",
      caption: "Add, remove, edit addresses or set as defult",
      url: "/addresses",
    },
    {
      img: "loginAndSecurity",
      background: "rgba(11, 125, 201, 0.59)",
      title: "Login & security",
      caption: "Manage, add, or remove your profile for public viewing",
      url: "/login-and-security",
    },
    {
      img: "publicProfile",
      background: "rgba(132, 29, 140, 0.59)",
      title: "Public profile",
      caption: "Manage, add, or remove your profile for public viewing",
      url: "/public-profile",
    },
  ];
  return (
    <div className={cn(Styles.container, "mt-2", "mt-md-3", "mt-lg-0")}>
      {navigations.map((item, i) => (
        <NavigationItem
          key={i}
          img={item.img}
          background={item.background}
          title={item.title}
          caption={item.caption}
          url={item.url}
        />
      ))}
    </div>
  );
};

export default AccountNavigation;
