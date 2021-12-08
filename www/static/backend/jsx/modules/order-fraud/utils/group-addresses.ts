import { AddressGeolocation } from "@admin/modules/order-fraud/ts/types/redux";
import { GroupAddress } from "@admin/modules/order-fraud/ts/types/address.type";

export const groupAddresses = (list: AddressGeolocation[]): GroupAddress[] => {
  const group: GroupAddress[] = [];
  list.forEach((address) => {
    const itemAddress = list.filter(
      (item) =>
        item.longitude === address.longitude &&
        item.latitude === address.latitude
    );
    if (
      itemAddress &&
      !group.find(
        (item) =>
          item.longitude === address.longitude &&
          item.latitude === address.latitude
      )
    ) {
      group.push({
        latitude: address.latitude,
        longitude: address.longitude,
        labels: itemAddress.map((item) => item.type),
      });
    }
  });
  return group;
};
