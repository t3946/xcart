import { ApiService } from "@client/modules/shared/services/api.service";
import { memoize } from "lodash";

const api = new ApiService();

function getOneOrder(id: string, func: (data: any) => void) {
  return api.get(`/account/api/orders/get-one-order/${id}`).then((e: any) => {
    func(e.data);
  });
}

export function memoizeGetOneOrder(id: string, func: (data: any) => void) {
  return memoize(() => getOneOrder(id, func));
}
