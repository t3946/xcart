import documentReady from "../utils/documentReady";
import {h, render} from 'preact';
import ButtonMoveUp from "../components/ButtonMoveUp";
import ButtonMoveDown from "../components/ButtonMoveDown";

documentReady(() => {

    let container = document.getElementById('containerUpDown');
    if(container){
        render(<div className="buttons-container">
            {/*<div className="columns small-12 buttons-container">*/}
                <ButtonMoveUp className="move-button" />
                <ButtonMoveDown className="move-button" />
            {/*</div>*/}
        </div>, container, container.firstChild);
    }

});
