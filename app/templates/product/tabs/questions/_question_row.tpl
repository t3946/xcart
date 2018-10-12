<div class="question-row">
    <div class="question">
        {include 'product/tabs/questions/_question_item.tpl'
        title = 'QUESTION'
        short = 'Q'
        text = $row->question
        byLine = $row->name|createByLine:$row->date}
    </div>
    {if $row->answered_on_page == 'Y' && $row->answer}
        {set $user_name = $row->user ? $row->user->getShortSurname() : ''}
        <div class="answer">
            {include 'product/tabs/questions/_question_item.tpl'
            title = 'BEST ANSWER'
            short = 'A'
            text = $row->answer
            byLine = $user_name|createByLine:$row->answered_date:true }
        </div>
    {/if}
</div>

