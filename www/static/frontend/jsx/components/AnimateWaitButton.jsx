import _ from 'lodash';

const DURATION_PRELOAD = 500;
const DURATION_SHOW_INFO = 3000;

class AnimateWaitButton {

    constructor(identifier) {
        identifier = identifier || '.wait-button';
        let element;

        if(typeof element == 'string') {
            this._element = document.querySelector(identifier);
        } else {
            this._element = identifier;
        }
    }

    start() {
        this._element.classList.add('wait', 'loading');

        setTimeout(() => {
            this._element.classList.remove('loading');

            setTimeout(() => {
                this._element.classList.remove('wait');
            }, DURATION_SHOW_INFO);
        }, DURATION_PRELOAD);
    }
}

export default function CreateWaitButton (identifier) {
    return new AnimateWaitButton(identifier);
}