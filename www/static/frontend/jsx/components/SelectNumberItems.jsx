import {h, render, Component} from "preact";

export default class SelectNumberItems extends Component {


    /**
     * Инициализация вплывающего окна для выбора колличества товара
     * @param props
     */
    constructor(props) {
        super(props);

        if (props.quantity > props.max) {
            return;
        }

        this.props = props;
        this.maxButtonValue = props.min + props.step * (this.props.number - 1);
        this.maxButtonValue = (props.max >= this.maxButtonValue) ? this.maxButtonValue : props.max;

        this.initState(props.quantity || props.min);
    }

    /**
     * Установка начального состояния
     * @param quantity
     */
    initState(quantity) {

        this.state = {
            'active': 'quantity' + quantity,
            'quantity': quantity,
            'userValue': quantity > this.maxButtonValue
        };
    }

    /**
     * Установка нового состояния
     * @param quantity
     */
    newState(quantity) {

        if (quantity >= this.props.min && quantity <= this.props.max) {

            if (this.props.step > 1 && (quantity % this.props.step) > 0) {
                return;
            }

            let detail = {
                quantity: quantity
            };
            // Событие сообщает странице о том что во всплывающем окне изменилось количество товара
            let event = new CustomEvent('component.select_number_items.change', {detail: detail});

            this.setState({
                'active': 'quantity' + quantity,
                'quantity': quantity,
                'userValue': quantity > this.maxButtonValue
            });

            document.dispatchEvent(event);
        }
    }

    /**
     * Вывод 1 кнопки с колличеством товара
     * @param index
     * @returns {*}
     */
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

    /**
     * Вывод набора кнопок с заданными значениями для колличества товара
     * @param number
     * @returns {*}
     */
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

    /**
     * Вывод на экран кнопкиб которая вызывает окно для ввода произвольного колличества товара
     * @returns {*}
     */
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

    /**
     * Вывод на экран окна с кнопками для выбора колличества товара
     * @param number
     * @returns {*}
     */
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

    /**
     * Сменить окно с кнопками на окно для ввода произвольного колличества товара
     */
    changeWindow() {
        let state = this.state;
        state.userValue = true;
        this.setState(state);
    }

    /**
     * Получить колличество товара, введенное пользователем
     */
    getUserQuantity() {
        let userEntered = parseInt(this.inputEl.value, 10);
        this.newState(userEntered);
    }

    /**
     * Установить фокус на поле ввода произвольного колличества товара
     */
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

    /**
     * Вывод на экран окна для ввода произвольного колличества товара
     * @returns {*}
     */
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
            // Ввод произвольного колличества товара
            return this.renderInputText();
        } else {
            // Выбор колличества товара из списка
            return this.renderRadioGroup(this.props.number);
        }

    }


}