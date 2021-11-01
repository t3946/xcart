// import React, { useEffect, useState } from "react";
// import { ApiService } from "@modules/shared/services/api.service";
// interface ProductDescription {
//   productId: number | string;
// }
// export const ProductDescription: React.FC<ProductDescription> = ({
//   productId,
// }) => {
//   const api = new ApiService();
//   const [description, setDescription] = useState("");
//   useEffect(() => {
//     api
//       .post(`/product/${productId}/get/description`)
//       .then((response) => setDescription(response.data));
//   }, []);
//   return <div>Test</div>;
// };
