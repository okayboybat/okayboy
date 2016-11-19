<?php
namespace Home\Ext\Shipping;
use Home\Ext\I_Shipping;
/**
 * 固定运费 送货方式的实现
 */
class Fixed implements I_Shipping
{

	private $title = '固定运费';
	private $description = '固定运费';
	private $key = 'fixedt';

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
		return 8.00;
	}
}

return 'Fixed';