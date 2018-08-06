import _ from 'lodash';

class SelectOption {
    constructor(button, element) {
        this.element = element;
        this.value = element.value;
        this.text = element.text;
        this.button = button;
    }

    setActive(element){
        //console.log(html);
        $(this.element).siblings().removeAttr('selected');
        this.element.setAttribute('selected', 'selected');
        element = element || document.createTextNode(this.text);
        this.button.innerHTML = ""
        this.button.append(element);
    }

    isActive(){
        return this.element.hasAttribute('selected');
    }

    isDisabled(){
        return this.element.hasAttribute('disabled') || this.element.hasAttribute('hidden');
    }
}

export default (button, element) => {
    return new SelectOption(button, element);
}