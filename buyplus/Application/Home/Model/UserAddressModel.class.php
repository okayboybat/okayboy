<?php

namespace Home\Model;

use Think\Model\RelationModel;

class UserAddressModel extends RelationModel
{
	// 定义关联关系
	protected $_link = [
		'province' => [
			'mapping_type'	=> self::BELONGS_TO,
			'foreign_key'	=> 'province_id',
			'class_name'	=> 'Region',// 当前关联的模型名
			],
		'city'	=> [
			'mapping_type'	=> self::BELONGS_TO,
			'foreign_key'	=> 'city_id',
			'class_name'	=> 'Region',// 当前关联的模型名
			],
		'area'	=> [
			'mapping_type'	=> self::BELONGS_TO,
			'foreign_key'	=> 'area_id',
			'class_name'	=> 'Region',// 当前关联的模型名
			],
	];
}