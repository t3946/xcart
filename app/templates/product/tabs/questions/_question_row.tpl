{if $row->answered_on_page == 'Y' && $row->answer}
    <div class="question-row">
        <div class="question">
            {include 'product/tabs/questions/_question_item.tpl'
            title = 'QUESTION'
            short = 'Q'
            text = $row->question
            byLine = $row->name|createByLine:$row->date}
        </div>
        <div class="answer">
            {include 'product/tabs/questions/_question_item.tpl'
            title = 'BEST ANSWER'
            short = 'A'
            text = $row->answer
            byLine = $row->user->firstname|createByLine:$row->answered_date:true}
        </div>
    </div>
{/if}
