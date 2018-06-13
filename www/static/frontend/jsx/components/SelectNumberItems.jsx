import {h, render, Component} from "preact";
//import renderToStringr from 'preact-render-to-string';
//import _ from 'lodash';

export default class ProductImageSlider extends Component {


    constructor(props) {
        super(props);

        this.number = (props.quantityAvaliable >= props.number) ? props.number : props.quantityAvaliable;

        if(props.quantity > props.quantityAvaliable){
            return;
        }

        this.props = props;
        this.initState(props.quantity || 1);
    }

    initState(quantity){

        this.state = {
            'active': 'quantity' + quantity,
            'quantity': quantity,
            'userValue': quantity > this.number
        };
    }

    newState(quantity){

        if(quantity <= this.props.quantityAvaliable){

            let detail = {
                quantity: quantity
            };
            let event = new CustomEvent('component.select_number_items.change', { detail: detail });

            this.setState({
                'active': 'quantity' + quantity,
                'quantity': quantity,
                'userValue': quantity > this.number
            });

            document.dispatchEvent(event);
        }
    }

    renderNumberItem(index) {
        let id = 'quantity' + index;
        return (
            <div>
                <input type="radio" id={id} name="quantity" value={index} key={index}
                       checked={this.state.active == id}/>
                <label htmlFor={id} onClick={ e => {this.newState(index)} }>{index}</label>
            </div>
        );
    }

    renderNumbersSelector(number) {

        let fields = [];
        for (let i = 0; i < number; i++) {
            fields.push(this.renderNumberItem(i + 1));
        }
        return (
            <div>{fields}</div>
        );
    }

    renderButton() {
        return (
            <div>
                <a onClick={ e => {this.changeWindow()} } className="add button waves waves-orange yellow">
                    <span className="text">Enter the amount</span>
                </a>
            </div>
        );
    }

    renderRadioGroup(number) {
        return (
            <div>
                <div className="title">
                    Select quantity
                </div>
                <div className="quantity-radio-group">
                    <form>
                        {this.renderNumbersSelector(number)}
                        {this.renderButton()}
                    </form>
                </div>
            </div>
        );
    }

    changeWindow(){
        let state = this.state;
        state.userValue = true;
        this.setState(state);
    }

    getUserQuantity(){
        let userEntered = parseInt(this.inputEl.value, 10);
        this.newState(userEntered);
    }

    renderInputText(number) {

        return (
            <div>
                <div className="title">
                    Quantity
                </div>
                <div className="input-quantity">
                    <input ref = {node => { this.inputEl = node; }} type='text' value={ this.state.quantity || number }/>
                </div>
                <div>
                    <a onClick={e => { this.getUserQuantity() } } className="add button waves waves-orange yellow">
                        <span className="text">Change</span>
                    </a>
                </div>
            </div>
        );
    }

    render(props, state) {

        if(state.userValue) {
            return this.renderInputText(props.number);
        } else {
            return this.renderRadioGroup(props.number);
        }

    }


}