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
			
			if($options->ap_quote_check)
			{
				/* Strip quoted text to stop inflation */
				/* Regex found on StackOverflow */
				/* https://stackoverflow.com/a/7208743 */
				$message = preg_replace('/\[quote=(.*?)\](((?R)|.*?)+)\[\/quote\]/is', '', $message);
			}

			if($options->ap_bbcode_check)
			{
				/* Strip BBCode tags to stop inflation */	
				$message = preg_replace('#\[[^]]*\]#', '', $message);
			}
			
			if($options->ap_url_check)
			{
				/* Strip URLs to stop inflation */
				/* Regex found on StackOverflow */
				/* https://stackoverflow.com/a/54808354 */
				$message = preg_replace('#((\w+:\/\/\S+)|(\w+[\.:]\w+\S+))[^\s,\.]#is', '', $message);
			}
			
			if($options->ap_newline_check)
			{
				/* Strip newlines to stop inflation */
				$message = preg_replace('/(\s)*/', '', $message);
			}
						
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

