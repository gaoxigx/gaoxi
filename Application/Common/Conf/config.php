<?php
return array(
	//'配置项'=>'配置值'
	//
	'DEFAULT_MODULE'        =>  'Console',  // 默认模块
	'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
	'DEFAULT_ACTION'        =>  'main', // 默认操作名称
	'SESSION_AUTO_START' => true, //是否开启SESSION
	// 添加数据库配置信息
	'DB_TYPE'=>'mysql',// 数据库类型
	'DB_HOST'=>'127.0.0.1',// 服务器地址
	'DB_NAME'=>'nicoproduct',// 数据库名
	'DB_USER'=>'root',// 用户名
	'DB_PWD'=>'root',// 密码
	'DB_PORT'=>3306,// 端口
	'DB_PREFIX'=>'nico_',// 数据库表前缀
	'DB_CHARSET'=>'utf8',// 数据库字符集
);