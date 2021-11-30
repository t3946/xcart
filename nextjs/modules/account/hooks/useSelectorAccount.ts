import { TypedUseSelectorHook, useSelector } from "react-redux";
import StoreInterface from "@client/modules/account/ts/types/store.type";

export default <TypedUseSelectorHook<StoreInterface>>useSelector;
