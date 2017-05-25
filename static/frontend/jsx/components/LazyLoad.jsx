export default class LazyLoad
{
    constructor(elements) {
        // this.attached = [];

        this.init(elements);
    }

    init(elements) {
        this.attached = [elements];

        this._bind();
    }

    attach(elements) {
        this.attached.push(elements);
    }

    _bind() {
        $(document).on('scroll', ()=>{

        });
    }
}
