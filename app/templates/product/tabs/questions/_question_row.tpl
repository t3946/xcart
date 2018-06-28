<div class="question-row">
    <div class="question">
        {include 'product/tabs/questions/_question_item.tpl'
            title = 'QUESTION'
            short = 'Q'
            text = $row->question
            byLine = ''
            autor = $row->name
            date = $row->date}
    </div>
    {if $row->answered_on_page == 'Y' && $row->answer}
    <div class="answear">
        {include 'product/tabs/questions/_question_item.tpl'
            title = 'BEST ANSWER'
            short = 'A'
            text = $row->answer
            byLine = ''
            author = $row->user->firstname
            date = $row->answered_date}
    </div>
    {/if}
</div>
