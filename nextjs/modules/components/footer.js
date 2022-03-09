
let funcRecalcFooter = () => {
    let footerHeight = $('footer').height();

    // $('.off-canvas-content').css('margin-bottom', -footerHeight);
    $('#content-wrapper').css('margin-bottom', -footerHeight);
    $('.off-canvas-content .push').css('height', footerHeight);
};

export default funcRecalcFooter;