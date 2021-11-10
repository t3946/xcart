import React from "react";
import EstimatedTimeArrivalTable, {
  TableTypes,
} from "@client/modules/account/components/orders/Decisions/EstimatedTimeArrival/EstimatedTimeArrivalTable";
const EstimatedTimeArrival: React.FC = () => {
  const mockData = [
    {
      name: "Cyprus Raw Umber Medium 4 Oz Vol",
      sku: "461-4210",
      amount: 2,
      date: "15-Sep-2021",
    },
    {
      name: "Cyprus Raw Umber Medium 4 Oz Vol",
      sku: "461-4210",
      amount: 2,
      date: "15-Sep-2021",
    },
    {
      name: "Cyprus Raw Umber Medium 4 Oz Vol",
      sku: "461-4210",
      amount: 2,
      date: "15-Sep-2021",
    },
  ];

  return (
    <div>
      <h1 className="decision-inner-header decision__inner-header">
        ETA Decision
      </h1>

      <EstimatedTimeArrivalTable
        tableType={TableTypes.inStock}
        items={mockData}
      />
      <EstimatedTimeArrivalTable
        tableType={TableTypes.outOfStock}
        items={mockData}
      />
      <EstimatedTimeArrivalTable
        tableType={TableTypes.discontinued}
        items={mockData}
      />
    </div>
  );
};

export default EstimatedTimeArrival;
