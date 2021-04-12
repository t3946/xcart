$(document).on('click', '.action_block.sort', function(e){
    return;

    e.preventDefault();
    $(this).toggleClass('active');
});

$(document).on('click', '.action_block.sort .options li', function(e){
    return;

    e.preventDefault();
    e.stopPropagation();
    let $this = $(this);

    if (!$this.hasClass('active')) {
        $('.action_block.sort .options li').removeClass('active');
        $this.addClass('active');
        $this.closest('.action_block.sort').find('.active_value').html($this.text());

        setTimeout(()=>{
            $this.closest('.action_block.sort').removeClass('active');
        }, 2000);


        window.loader.load();
        window.loader.load(()=>{
            $.ajax({
                url: window.location,
                method: 'POST',
                data: {sort: $(this).data('value')},
                success : (data)=>{

                    window.loader.load();
                    window.location.reload();
                }
            })
        });
    }
    else {
        $this.closest('.action_block.sort').removeClass('active');
    }
});