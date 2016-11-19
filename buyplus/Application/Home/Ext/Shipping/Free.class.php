<?php
namespace Home\Ext\Shipping;
use Home\Ext\I_Shipping;
/**
 * 免运费 送货方式的实现
 */
class Free implements I_Shipping
{

	private $title = '免运费';
	private $description = '免运费';
	private $key = 'free';

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
		return 0.00;
	}
}

return 'Free';