<?php

namespace apathy\MinChars\XF\Entity;

use XF\Mvc\Entity\Structure;

class Post extends XFCP_Post
{
	protected function adjustUserMessageCountIfNeeded($amount)
	{
		if ($this->user_id
			&& $this->User
			&& !empty($this->Thread->Forum->count_messages)
			&& $this->Thread->discussion_state == 'visible')
		{
			$options = \XF::options();

			// Only update if message is greater than X chars
			$message = $this->message;
			$chars = strlen($message);

			if($chars > $options->ap_char_limit)
			{
				$this->User->fastUpdate('message_count', max(0, $this->User->message_count + $amount));
			}
		}
	}
}