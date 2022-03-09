interface BreadcrumbsAddressDto {
  name: string; //breadcrumb link name
  path: string; //domain relative path. form example /account or /account/login
}

export const setBreadcrumbsAddress = (address: BreadcrumbsAddressDto): any => ({
  type: "SET_BREADCRUMBS_ADDRESS",
  address,
});

export const setBreadcrumbsAddresses = (
  addresses: BreadcrumbsAddressDto[]
): any => ({
  type: "SET_BREADCRUMBS_ADDRESSES",
  addresses,
});
