import $ from 'jquery';

export default (type, data, target = document) => {
    $(target).trigger(type, data);
};