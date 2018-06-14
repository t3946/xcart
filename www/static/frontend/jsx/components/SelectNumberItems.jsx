import {h, render, Component} from "preact";
//import renderToStringr from 'preact-render-to-string';
//import _ from 'lodash';

export default class ProductImageSlider extends Component {


    constructor(props) {
        super(props);

        if (props.quantity > props.max) {
            return;
        }

        this.props = props;
        this.maxButtonValue = props.min + props.step * (this.props.number - 1);
        this.maxButtonValue = (props.max >= this.maxButtonValue) ? this.maxButtonValue : props.max;

        //console.log(props.quantity);
        this.initState(props.quantity || props.min);
    }

    initState(quantity) {

        this.state = {
            'active': 'quantity' + quantity,
            'quantity': quantity,
            'userValue': quantity > this.maxButtonValue
        };
    }

    newState(quantity) {

        if (quantity >= this.props.min && quantity <= this.props.max) {

            if (this.props.step > 1 && (quantity % this.props.step) > 0) {
                return;
            }

            let detail = {
                quantity: quantity
            };
            let event = new CustomEvent('component.select_number_items.change', {detail: detail});

            this.setState({
                'active': 'quantity' + quantity,
                'quantity': quantity,
                'userValue': quantity > this.maxButtonValue
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
                <label htmlFor={id} onClick={e => {
                    this.newState(index)
                }}>{index}</label>
            </div>
        );
    }

    renderNumbersSelector(number) {

        let fields = [];
        let quantity = this.props.min;
        //let nButton = 1;

        while (quantity <= this.maxButtonValue) {// && nButton <= number
            fields.push(this.renderNumberItem(quantity));
            quantity += this.props.step;
            //nButton ++;
        }

        return (
            <div>{fields}</div>
        );
    }

    renderButton() {
        if (this.props.max - this.maxButtonValue < this.props.step) {
            return;
        }
        return (
            <div>
                <a onClick={e => {
                    this.changeWindow()
                }} className="add button button-amount">
                    <span className="text">Other amount</span>
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

    changeWindow() {
        let state = this.state;
        state.userValue = true;
        this.setState(state);
    }

    getUserQuantity() {
        let userEntered = parseInt(this.inputEl.value, 10);
        this.newState(userEntered);
    }

    setFocus() {
        if (this.state.userValue) {
            this.inputEl.focus();
            this.inputEl.select();
        }
    }

    componentDidUpdate() {
        this.setFocus();
    }

    componentDidMount() {
        this.setFocus();
    }

    renderInputText() {

        let value = (this.state.quantity && this.state.quantity > this.maxButtonValue) ? this.state.quantity : (this.maxButtonValue + this.props.step);
        value = parseInt(value, 10);
        let max = parseInt(this.props.max, 10);

        return (
            <div>
                <div className="title">
                    Quantity
                </div>
                <div className="max-number">
                    Maximum amount: {max}
                </div>
                <div className="input-quantity">
                    <input ref={node => {
                        this.inputEl = node;
                    }} type="number" value={value}
                           min={this.props.min} max={this.props.max} step={this.props.step} autofocus/>
                </div>
                <div>
                    <a onClick={e => {
                        this.getUserQuantity()
                    }} className="add button button-change waves waves-orange yellow">
                        <span className="text">Set</span>
                    </a>
                </div>
            </div>
        );
    }

    render(props, state) {

        if (state.userValue) {
            return this.renderInputText();
        } else {
            return this.renderRadioGroup(this.props.number);
        }

    }


}