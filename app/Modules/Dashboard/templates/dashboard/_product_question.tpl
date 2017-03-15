{foreach $questions as $question}
    <a target="_blank" href="/admin/product_question_search.php?mode=search&status={$question->status}&from_dashboard=Y">
        {$question->getField('status')->toText()} ({$question->id})
    </a>
{/foreach}