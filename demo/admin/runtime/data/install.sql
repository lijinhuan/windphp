
CREATE TABLE IF NOT EXISTS `department` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `parentid` int(10) unsigned NOT NULL,
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

INSERT INTO `department` (`id`, `name`, `parentid`, `level`) VALUES
(1, '技术部', 0, 1);

CREATE TABLE IF NOT EXISTS `menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL DEFAULT '',
  `parentid` smallint(6) NOT NULL DEFAULT '0',
  `controller` char(20) NOT NULL DEFAULT '',
  `action` char(20) NOT NULL DEFAULT '',
  `data` char(100) NOT NULL DEFAULT '',
  `listorder` smallint(6) unsigned NOT NULL DEFAULT '0',
  `display` enum('1','0') NOT NULL DEFAULT '1',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `listorder` (`listorder`),
  KEY `parentid` (`parentid`),
  KEY `contro_action` (`controller`,`action`),
  KEY `level` (`level`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=205 ;


INSERT INTO `menu` (`id`, `name`, `parentid`, `controller`, `action`, `data`, `listorder`, `display`, `level`) VALUES
(1, '我的中心', 0, 'myPanel', 'class1', '', 1001, '1', 1),
(2, '个人信息', 1, 'panel', 'class2_1', '', 1, '1', 2),
(3, '修改个人信息', 2, 'myPanel', 'modifyInfo', '', 1, '1', 3),
(4, '密码修改', 2, 'myPanel', 'modifyPassword', '', 1, '1', 3),
(5, '后台管理', 0, 'backend', 'class1', '', 900, '1', 1),
(6, '管理员设置', 5, 'backend', 'class2_1', '', 1, '1', 2),
(7, '角色设置', 5, 'backend', 'class2_2', '', 1, '1', 2),
(8, '菜单功能', 5, 'Menu', 'class2_3', '', 1, '1', 2),
(9, '管理员列表', 6, 'Administrators', 'Run', '', 1, '1', 3),
(10, '添加管理员', 6, 'Administrators', 'Cadd', '', 1, '1', 3),
(11, '添加菜单', 13, 'Menu', 'Add', '', 1, '0', 4),
(12, '添加角色', 7, 'Role', 'Cadd', '', 1, '1', 3),
(13, '后台菜单', 8, 'Menu', 'List', '', 1, '1', 3),
(14, '删除菜单', 13, 'Menu', 'Delete', '', 1, '0', 4),
(15, '角色列表', 7, 'Role', 'Run', '', 0, '1', 3),
(16, '修改角色', 15, 'Role', 'Cedit', '', 0, '0', 4),
(17, '删除角色', 15, 'Role', 'Cdel', '', 0, '0', 4),
(18, '角色权限设置', 15, 'Role', 'PrivSetting', '', 0, '0', 4),
(19, '修改菜单', 13, 'Menu', 'Edit', '', 0, '0', 4),
(20, '删除管理员', 9, 'Administrators', 'Cdel', '', 0, '0', 4),
(21, '修改管理员', 9, 'Administrators', 'Cedit', '', 0, '0', 4),
(22, '后台操作', 5, 'backend', 'class2_4', '', 0, '1', 2),
(23, '操作记录', 22, 'OptLog', 'Run', '', 0, '1', 3),
(201, '部门管理', 22, 'Department', 'Run', '', 0, '1', 3),
(202, '添加部门', 201, 'Department', 'Cadd', '', 0, '1', 4),
(203, '修改部门', 201, 'Department', 'Cedit', '', 0, '1', 4),
(204, '删除部门', 201, 'Department', 'Cdel', '', 0, '1', 4),
(205, '联动菜单', 8, 'Linkage', 'Run', '', 0, '1', 3);

CREATE TABLE IF NOT EXISTS `role` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `rolename` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rolename` (`rolename`),
  KEY `listorder` (`listorder`),
  KEY `disabled` (`disabled`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

INSERT INTO `role` (`id`, `rolename`, `description`, `listorder`, `disabled`) VALUES
(1, '超级管理员', '超级管理员', 0, 0),
(19, '网站管理员', '网站管理员', 0, 0);

CREATE TABLE IF NOT EXISTS `role_priv` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `priv` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roleid` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `role_priv` (`id`, `priv`) VALUES
(19, '["1","2","3","4","5","6","9","20","21","10","7","12","15","16","17","18","22","23","201","202","203","204"]');

CREATE TABLE IF NOT EXISTS `user` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `roleid` varchar(255) DEFAULT '0',
  `department_id` mediumint(6) unsigned DEFAULT '0' COMMENT '部门id',
  `lastloginip` varchar(15) DEFAULT NULL,
  `lastlogintime` int(10) unsigned DEFAULT '0',
  `email` varchar(40) DEFAULT NULL,
  `realname` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL COMMENT '手机号码',
  `qq` varchar(50) NOT NULL,
  `salt` varchar(64) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `position` varchar(32) NOT NULL COMMENT '职位',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='后台用户表' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_history` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `roleid` varchar(255) DEFAULT '0',
  `department_id` mediumint(6) unsigned DEFAULT NULL COMMENT '部门id',
  `lastloginip` varchar(15) DEFAULT NULL,
  `lastlogintime` int(10) unsigned DEFAULT '0',
  `email` varchar(40) DEFAULT NULL,
  `realname` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL COMMENT '手机号码',
  `qq` varchar(50) NOT NULL,
  `salt` varchar(64) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `position` varchar(32) NOT NULL,
  KEY `username` (`username`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='后台用户表' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_opt_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `controller` varchar(16) NOT NULL,
  `action` varchar(16) NOT NULL,
  `get` varchar(255) NOT NULL,
  `post` text NOT NULL,
  `file` varchar(255) NOT NULL,
  `ip` char(15) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `linkage` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `keyid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`keyid`),
  KEY `parentid` (`parentid`,`listorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3370 ;