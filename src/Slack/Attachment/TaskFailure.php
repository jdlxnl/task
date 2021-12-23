<?php

namespace Jdlx\Task\Slack\Attachment;

use Jdlx\Slack\Attachment\AttachmentBuilder;
use Jdlx\Task\Slack\Attachment\Traits\AdsJobFailure;
use Jdlx\Task\Slack\Attachment\Traits\AdsTaskFailure;

class TaskFailure extends AttachmentBuilder
{

    use AdsJobFailure, AdsTaskFailure;

    /**
     * @return \Illuminate\Notifications\Messages\SlackAttachment
     */
    public function toSlackAttachment()
    {
        $attachment = parent::toSlackAttachment();
        $attachment->content($this->getLog());
        return $attachment;
    }
}
