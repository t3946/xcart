import documentReady from "../utils/documentReady";
import {h, render} from 'preact';
import ButtonMoveUp from "../components/ButtonMoveUp";
import ButtonMoveDown from "../components/ButtonMoveDown";

documentReady(() => {

    let container = document.getElementById('containerUpDown');
    console.log(container);
    if(container){
        render(<div><ButtonMoveUp /><ButtonMoveDown /></div>, container, container.firstChild);
    }

});
