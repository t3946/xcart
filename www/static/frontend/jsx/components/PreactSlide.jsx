import { h, Component, render } from 'preact';
import _ from 'lodash';


export default class PreactSlide extends Component
{
    constructor() {
        super();
    }

    render({children}) {
        console.log(children);

        return <div className={'wrap'} style={'overflow:hidden'}>
            {children}
        </div>;
    }
}