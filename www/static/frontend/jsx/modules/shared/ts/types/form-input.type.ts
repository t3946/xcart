export interface FormInputPropsDto {
  required?: boolean;
  name: string;
  label: string;
  type?: string;
  error: boolean;
  as?: string;
  valid: boolean;
}

export interface HelpFormInputPropsDto extends FormInputPropsDto {
  clear: (field: string, value: string, shouldValidate?: boolean) => void;
  value: string;
  errorMessage: string;
}

export interface HelpFormPhoneInputPropsDto extends HelpFormInputPropsDto {
  extName: string;
  errorExt: boolean;
  valueExt: string;
  validExt: boolean;
}

export interface ErrorMessageDto {
  errorMessage: string;
}
