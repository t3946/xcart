import storeApp from "../stores/StoreApp";
import {hideAll} from '../redusers/appHeadReduser';


(()=>{
    $('.shadow').on('click touchstart', hideAll);

    let unsubscribe = storeApp.subscribe(()=>{
        let state = storeApp.getState();

        if (state.frontend) {
            if ( state.frontend.darkness) {
                $('.shadow').addClass('active');
            }
            else {
                $('.shadow').removeClass('active');
            }
        }
    });
})();