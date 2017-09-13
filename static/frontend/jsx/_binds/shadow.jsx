import storeApp from "../stores/StoreApp";

$('.shadow').on('click touchstart', ()=> {
    storeApp.dispatch({type:'SET', data: {
        frontend: {
            darkness: false,
            header: {
                active: null,
            }
        }
    }});
});

(()=>{
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