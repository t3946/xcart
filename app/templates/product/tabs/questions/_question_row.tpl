<div class="question-row">
    <div class="question">
        {set $lbl}{t 'QUESTION'}{/set}
        {include 'product/tabs/questions/_question_item.tpl'
        title = $lbl
        short = 'Q'
        text = $row->question
        byLine = $row->name|createByLine:$row->date}
    </div>
    {if $row->answered_on_page == 'Y' && $row->answer}
        {set $user_name = $row->user ? $row->user->getShortSurname() : ''}
        <div class="answer">
            {set $lbl}{t 'BEST ANSWER'}{/set}
            {include 'product/tabs/questions/_question_item.tpl'
            title = $lbl
            short = 'A'
            text = $row->answer
            byLine = $user_name|createByLine:$row->answered_date:true }
        </div>
    {/if}
</div>

