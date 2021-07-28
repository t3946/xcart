export enum EOpen {
    Error = 'error',
    Warning = 'warning',
    Info = 'info',
    Success = 'success',
}
export interface IOpen {
    status: EOpen,
    state: boolean,
    message: string
}
export const defaultOpen : IOpen = {
    state: false,
    status: EOpen.Success,
    message: ''
}
export const getMessage = (status: EOpen, message: string) : IOpen => {
    return {...defaultOpen, ...{status, message}};
}