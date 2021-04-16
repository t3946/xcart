import { render } from 'preact';
import YourOrder from "@/components/help-center/your-order/YourOrder";
import NavigateMenuRoutes from "@/components/help-center/navigate-menu/NavigateMenuRoutes";

(() => {
    const elem = document.getElementsByClassName( 'help' )[0];

    console.log(elem)

    render(
    <NavigateMenuRoutes/>,
        elem
    )
})()