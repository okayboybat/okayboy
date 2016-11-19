<?php


namespace Home\Ext\Shipping;
use Home\Ext\I_Shipping;
// EMS 货运方式的实现类文件

class EMS implements I_Shipping 
{

	private $title = 'EMS';
	private $description = 'EMS';
	private $key = 'ems';

	public function getTitle()
	{
		return $this->title;
	}
	public function getKey()
	{
		return $this->key;
	}
	public function getDescription()
	{
		return $this->description;
	}

	public function getPrice($to='', $weight=0, $size=[])
	{
		return 10.00;
	}
}

return 'EMS';