<?php
namespace Xcart;

class AttentionTag extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['status_id'];
        $this->sPrimaryTable = 'attention_tags_values';
        parent::__construct($aParams);

    }

    public function getStatus()
    {
        return $this->getField('status');
    }

    public function getStatusId()
    {
        return $this->getField('status_id');
    }
}