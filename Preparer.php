<?php

namespace apathy\DailyGoal\XF\Service\Message;

class Preparer extends XFCP_Preparer
{
	public function afterInsert()
	{
		parent::afterInsert();

		$count = \XF::registry()->get('ap_daily_goal');

		\XF::registry()->set('ap_daily_goal', 1);
	}
}