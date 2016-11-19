<?php
namespace Home\Model;

use Think\Model\RelationModel;

class GoodsAttributeValueModel extends RelationModel
{

	protected $_link = [
		'AttributeOption' => [
			'mappting_type'	=> self::BELONGS_TO,
			'foreign_key'	=> 'attribute_option_id',
		]
	];
}