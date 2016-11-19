<?php

// 项目中各个模块的通用函数
// 该文件会被自动的载入


function UC($key)
{
	// 操作 setting 表
	$row = M('Setting')->field('value')->where(['key'=>$key])->find();

	return $row ? $row['value'] : null;
}