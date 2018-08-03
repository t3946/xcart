import _ from 'lodash';

class SelectOption {
    constructor(element) {
        this.element = element;
        this.value = element.value;
        this.text = element.text;
    }

    setActive(){
        $(this.element).siblings().removeAttr('selected');
        this.element.setAttribute('selected', 'selected');
    }

    isActive(){
        return this.element.hasAttribute('selected');
    }

    isDisabled(){
        return this.element.hasAttribute('disabled') || this.element.hasAttribute('hidden');
    }
}

export default (element) => {
    return new SelectOption(element);
}