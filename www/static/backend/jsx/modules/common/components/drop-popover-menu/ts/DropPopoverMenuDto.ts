export default interface DropPopoverMenuDto {
  onSelect?(value: string): void;
  button: any;
  menu: any;
  menuClasses: string | Array<any> | Record<string, any>;
  ref: any;
}
