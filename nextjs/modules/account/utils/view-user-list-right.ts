import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";

export function viewUserListRight(role: UserPrivateVariantsEnum): string {
  switch (role) {
    case UserPrivateVariantsEnum.EDIT:
      return "Editor";
    case UserPrivateVariantsEnum.VIEW:
      return "Viewer";
    case UserPrivateVariantsEnum.OWNER:
      return "List owner";
  }
}
