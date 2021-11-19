import HelpfulCheck from "@client/modules/icon/components/account/check/HelpfulCheck";
import React from "react";
import {
  markHelpfulAction,
  unmarkHelpfulAction,
} from "@client/jsx/redux/actions/ProductActions";
import { useDispatch } from "react-redux";

interface IProps {
  reviewId: number;
  isHelpful: boolean;
}

const MarkAsHelpful: React.FC<IProps> = function (
  props: IProps
) {
  const [isSubmitting, setIsSubmitting] = React.useState(false);
  const [markedAsHelpful, setMarkedAsHelpful] = React.useState(false);
  const dispatch = useDispatch();

  function helpfulClickHandler() {
    setIsSubmitting(true);

    dispatch(
      markHelpfulAction({
        data: {
          reviewId: props.reviewId,
        },
        success: () => {
          setIsSubmitting(false);
          setMarkedAsHelpful(true);

          setTimeout(() => {
            setMarkedAsHelpful(false);
          }, 1000);
        },
      })
    );
  }

  function unhelpfulClickHandler() {
    setIsSubmitting(true);

    dispatch(
      unmarkHelpfulAction({
        data: {
          reviewId: props.reviewId,
        },
        success: () => {
          setIsSubmitting(false);
        },
      })
    );
  }

  function buttonTemplate() {
    if (markedAsHelpful) {
      return (
        <p className={"helpful-thanks m-0"}>
          <HelpfulCheck className={"me-lg-12 me-md-2 me-1"} />
          Thank you for your feedback!
        </p>
      );
    }

    if (props.isHelpful === false) {
      return (
        <button
          className={"form-button form-button__outline w-100 w-md-auto"}
          onClick={helpfulClickHandler}
          disabled={isSubmitting}
        >
          helpful
        </button>
      );
    } else {
      return (
        <button
          className={"form-button form-button__outline w-100 w-md-auto"}
          onClick={unhelpfulClickHandler}
          disabled={isSubmitting}
        >
          unhelpful
        </button>
      );
    }
  }

  return (
    <div
      className={
        "d-flex flex-column flex-md-row align-items-center mx--10 mx-md-0 mt-3"
      }
    >
      {buttonTemplate()}

      <a href="#" className={"common-link review__report-abuse-link"}>
        Report abuse
      </a>
    </div>
  );
};

export default MarkAsHelpful;
