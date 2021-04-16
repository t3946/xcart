import React from 'preact'
import NavigateMenuItem from "@/components/help-center/navigate-menu/NavigateMenuItem";

const NavigateMenu = () => {
    return(
       <div>
            <NavigateMenuItem link={'/help/'} text={'Your order'} image={''}/>
           <NavigateMenuItem link={'/help/add'} text={'Your order'} image={''}/>
       </div>
    )
}

export default NavigateMenu