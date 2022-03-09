import { ApiService } from "@modules/shared/services/api.service";
import memoize from "lodash/memoize";

const api = new ApiService();

function getOneOrder(id: string, func: (data: any) => void) {
  return api.get(`/account/api/orders/get-one-order/${id}`).then((e: any) => {
    func(e.data);
  });
}

export function memoizeGetOneOrder(id: string, func: (data: any) => void): any {
  return memoize(() => getOneOrder(id, func));
}
