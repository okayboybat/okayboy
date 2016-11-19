<?php

namespace Home\Model;

use Think\Model\RelationModel;

class GoodsAttributeModel extends RelationModel
{
	// 配置关联
	protected $_link = [


		'GoodsAttributeValue' => [
			'mapping_type'	=> self::HAS_MANY, // 拥有多个, 一个商品属性拥有多个商品属性值
			// self::HAS_ONE,BELONGS_TO,HAS_MANY,MANY_TO_MANY
			'foreign_key'	=> 'goods_attribute_id', // 关联字段
		],
		'Attribute' => [
			'mapping_type'	=> self::BELONGS_TO,
			'foreign_key'	=> 'attribute_id',
		]
	];
}