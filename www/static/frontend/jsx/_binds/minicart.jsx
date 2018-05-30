import { h, render } from 'preact';
import { Provider } from 'preact-redux';
import MiniCart from "../components/MiniCart";
import storeCart from '../stores/StoreCart';

let minicart = document.querySelector('body #search_container .minicart');

if (minicart) {
    render(<Provider store={storeCart}><MiniCart /></Provider>, minicart);
}