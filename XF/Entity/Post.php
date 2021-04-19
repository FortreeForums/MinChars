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
			$message = $this->message;

			/* Strip BBCode tags to stop inflation */		
			$message = preg_replace('#\[[^]]*\]#', '', $message);
			
			/* Strip URLs to stop inflation */
			/* Regex found on Github */
			/* https://gist.github.com/madeinnordeste/e071857148084da94891 */
			$message = preg_replace('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $message);
			
			/* Strip newlines to stop inflation */
			$message = preg_replace('/(\s)*/', '', $message);
			
			/* Finally count the characters */
			$chars = strlen($message);

			/* Only update if message is greater than X chars */
			if($chars > $options->ap_char_limit)
			{
				$this->User->fastUpdate('message_count', max(0, $this->User->message_count + $amount));
			}
		}
	}
}
