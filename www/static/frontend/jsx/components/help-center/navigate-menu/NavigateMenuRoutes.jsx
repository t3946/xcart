import React from 'react'
import {Router} from 'preact-router';
import NavigateMenu from "@/components/help-center/navigate-menu/NavigateMenu";
import YourOrder from "@/components/help-center/your-order/YourOrder";
import Example from "@/components/help-center/accordion/Accordion";

const NavigateMenuRoutes = () => {
    return(
        <>
            <NavigateMenu/>
            <Example/>
            <Router>
                <YourOrder path='/help/'/>
            </Router>
        </>
    )
}
export default NavigateMenuRoutes