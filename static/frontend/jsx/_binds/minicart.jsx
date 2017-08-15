import { h, render } from 'preact';
import MiniCart from "../components/MiniCart";

let minicart = document.querySelector('body #search_container .minicart');

if (minicart) {
    render(<MiniCart />, minicart);
}