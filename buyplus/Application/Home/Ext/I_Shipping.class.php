<?php

namespace Home\Ext;


interface I_Shipping 
{

	public function getKey();
	public function getTitle();
	public function getDescription();
	public function getPrice($to='', $weight=0, $size=[]);
}