/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : test

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 26/06/2020 19:55:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tn_category
-- ----------------------------
DROP TABLE IF EXISTS `tn_category`;
CREATE TABLE `tn_category`  (
  `cate_id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cover_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
  PRIMARY KEY (`cate_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tn_category
-- ----------------------------
INSERT INTO `tn_category` VALUES (1, '影视', '');
INSERT INTO `tn_category` VALUES (2, '音乐', '');
INSERT INTO `tn_category` VALUES (3, '生活', '');
INSERT INTO `tn_category` VALUES (4, '爱好', '');
INSERT INTO `tn_category` VALUES (5, '运动', '');
INSERT INTO `tn_category` VALUES (6, '旅行', '');
INSERT INTO `tn_category` VALUES (7, '知识', '');
INSERT INTO `tn_category` VALUES (8, '动漫', '');
INSERT INTO `tn_category` VALUES (9, '情感', '');
INSERT INTO `tn_category` VALUES (10, '娱乐', '');
INSERT INTO `tn_category` VALUES (11, '萌宠', '');
INSERT INTO `tn_category` VALUES (12, '自然', '');
INSERT INTO `tn_category` VALUES (13, '美食', '');
INSERT INTO `tn_category` VALUES (14, '职场', '');
INSERT INTO `tn_category` VALUES (15, '摄影', '');
INSERT INTO `tn_category` VALUES (16, '时尚', '');
INSERT INTO `tn_category` VALUES (17, '阅读', '');
INSERT INTO `tn_category` VALUES (18, '游戏', '');
INSERT INTO `tn_category` VALUES (19, '艺术', '');

-- ----------------------------
-- Table structure for tn_comment
-- ----------------------------
DROP TABLE IF EXISTS `tn_comment`;
CREATE TABLE `tn_comment`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL,
  `uid` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `create_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 296 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tn_discuss
-- ----------------------------
DROP TABLE IF EXISTS `tn_discuss`;
CREATE TABLE `tn_discuss`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `title` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `introduce` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `read_count` int(255) NOT NULL DEFAULT 0,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tn_discuss
-- ----------------------------
INSERT INTO `tn_discuss` VALUES (1, 170 ,1， '哪个句子，曾给你很多动力和希望？', '有些句子，能让你如沐春风，恍然大悟，甚至在你艰难的时候让你感受到心灵上的慰藉。', 85, 1598465487);
INSERT INTO `tn_discuss` VALUES (2, 170 ,1， '你是怎样通过文字赚取第一桶金的？', '你通过文字赚取了第一桶金没有？赚了多少，当时的心情如何？简单描述一下吧', 58, 0);
INSERT INTO `tn_discuss` VALUES (14, 2 , 1，'让人醍醐灌顶的句子', '分享你见过最醍醐灌顶的句子', 9, 1591703545);

-- ----------------------------
-- Table structure for tn_follow
-- ----------------------------
DROP TABLE IF EXISTS `tn_follow`;
CREATE TABLE `tn_follow`  (
  `uid` int(11) NOT NULL,
  `follow_uid` int(11) NOT NULL,
  UNIQUE INDEX `uid`(`uid`, `follow_uid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tn_follow
-- ----------------------------
INSERT INTO `tn_follow` VALUES (1, 2);
INSERT INTO `tn_follow` VALUES (1, 3);
INSERT INTO `tn_follow` VALUES (2, 170);
INSERT INTO `tn_follow` VALUES (3, 153);
INSERT INTO `tn_follow` VALUES (3, 168);
INSERT INTO `tn_follow` VALUES (9, 2);
INSERT INTO `tn_follow` VALUES (11, 0);
INSERT INTO `tn_follow` VALUES (11, 2);
INSERT INTO `tn_follow` VALUES (11, 210);
INSERT INTO `tn_follow` VALUES (13, 0);
INSERT INTO `tn_follow` VALUES (13, 2);
INSERT INTO `tn_follow` VALUES (13, 153);
INSERT INTO `tn_follow` VALUES (13, 229);
INSERT INTO `tn_follow` VALUES (30, 229);
INSERT INTO `tn_follow` VALUES (30, 242);
INSERT INTO `tn_follow` VALUES (30, 252);
INSERT INTO `tn_follow` VALUES (42, 153);
INSERT INTO `tn_follow` VALUES (51, 2);
INSERT INTO `tn_follow` VALUES (71, 2);
INSERT INTO `tn_follow` VALUES (74, 2);
INSERT INTO `tn_follow` VALUES (78, 2);
INSERT INTO `tn_follow` VALUES (87, 1);
INSERT INTO `tn_follow` VALUES (107, 2);
INSERT INTO `tn_follow` VALUES (162, 2);
INSERT INTO `tn_follow` VALUES (166, 2);
INSERT INTO `tn_follow` VALUES (168, 2);
INSERT INTO `tn_follow` VALUES (170, 2);
INSERT INTO `tn_follow` VALUES (186, 2);
INSERT INTO `tn_follow` VALUES (197, 2);
INSERT INTO `tn_follow` VALUES (207, 2);
INSERT INTO `tn_follow` VALUES (217, 2);
INSERT INTO `tn_follow` VALUES (249, 226);
INSERT INTO `tn_follow` VALUES (261, 2);
INSERT INTO `tn_follow` VALUES (261, 229);
INSERT INTO `tn_follow` VALUES (262, 261);
INSERT INTO `tn_follow` VALUES (268, 2);
INSERT INTO `tn_follow` VALUES (268, 261);
INSERT INTO `tn_follow` VALUES (278, 2);
INSERT INTO `tn_follow` VALUES (278, 170);
INSERT INTO `tn_follow` VALUES (279, 226);
INSERT INTO `tn_follow` VALUES (283, 2);
INSERT INTO `tn_follow` VALUES (288, 2);
INSERT INTO `tn_follow` VALUES (293, 210);
INSERT INTO `tn_follow` VALUES (293, 229);
INSERT INTO `tn_follow` VALUES (295, 252);

-- ----------------------------
-- Table structure for tn_group
-- ----------------------------
DROP TABLE IF EXISTS `tn_group`;
CREATE TABLE `tn_group`  (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `create_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tn_group
-- ----------------------------
INSERT INTO `tn_group` VALUES (1, '超级管理员', 1587854384);
INSERT INTO `tn_group` VALUES (2, '默认用户组', 1568803859);

-- ----------------------------
-- Table structure for tn_group_rule
-- ----------------------------
DROP TABLE IF EXISTS `tn_group_rule`;
CREATE TABLE `tn_group_rule`  (
  `group_id` int(11) NOT NULL,
  `rule_id` int(11) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tn_group_rule
-- ----------------------------
INSERT INTO `tn_group_rule` VALUES (1, 28);
INSERT INTO `tn_group_rule` VALUES (1, 15);
INSERT INTO `tn_group_rule` VALUES (1, 38);
INSERT INTO `tn_group_rule` VALUES (1, 4);
INSERT INTO `tn_group_rule` VALUES (1, 1);
INSERT INTO `tn_group_rule` VALUES (1, 31);
INSERT INTO `tn_group_rule` VALUES (1, 11);
INSERT INTO `tn_group_rule` VALUES (1, 16);
INSERT INTO `tn_group_rule` VALUES (1, 9);
INSERT INTO `tn_group_rule` VALUES (1, 23);
INSERT INTO `tn_group_rule` VALUES (1, 7);
INSERT INTO `tn_group_rule` VALUES (1, 12);
INSERT INTO `tn_group_rule` VALUES (1, 13);
INSERT INTO `tn_group_rule` VALUES (1, 14);
INSERT INTO `tn_group_rule` VALUES (1, 2);
INSERT INTO `tn_group_rule` VALUES (1, 8);
INSERT INTO `tn_group_rule` VALUES (1, 17);
INSERT INTO `tn_group_rule` VALUES (1, 18);
INSERT INTO `tn_group_rule` VALUES (1, 19);
INSERT INTO `tn_group_rule` VALUES (1, 21);
INSERT INTO `tn_group_rule` VALUES (1, 22);
INSERT INTO `tn_group_rule` VALUES (1, 10);
INSERT INTO `tn_group_rule` VALUES (1, 24);
INSERT INTO `tn_group_rule` VALUES (1, 25);
INSERT INTO `tn_group_rule` VALUES (1, 26);
INSERT INTO `tn_group_rule` VALUES (1, 29);
INSERT INTO `tn_group_rule` VALUES (1, 5);
INSERT INTO `tn_group_rule` VALUES (1, 27);
INSERT INTO `tn_group_rule` VALUES (1, 30);
INSERT INTO `tn_group_rule` VALUES (1, 6);
INSERT INTO `tn_group_rule` VALUES (1, 32);
INSERT INTO `tn_group_rule` VALUES (1, 33);
INSERT INTO `tn_group_rule` VALUES (1, 34);
INSERT INTO `tn_group_rule` VALUES (1, 35);
INSERT INTO `tn_group_rule` VALUES (1, 36);
INSERT INTO `tn_group_rule` VALUES (1, 37);
INSERT INTO `tn_group_rule` VALUES (1, 3);
INSERT INTO `tn_group_rule` VALUES (1, 39);
INSERT INTO `tn_group_rule` VALUES (1, 40);
INSERT INTO `tn_group_rule` VALUES (1, 41);
INSERT INTO `tn_group_rule` VALUES (1, 42);
INSERT INTO `tn_group_rule` VALUES (1, 43);
INSERT INTO `tn_group_rule` VALUES (1, 44);
INSERT INTO `tn_group_rule` VALUES (1, 60);
INSERT INTO `tn_group_rule` VALUES (1, 61);
INSERT INTO `tn_group_rule` VALUES (1, 62);
INSERT INTO `tn_group_rule` VALUES (1, 63);
INSERT INTO `tn_group_rule` VALUES (1, 64);
INSERT INTO `tn_group_rule` VALUES (1, 65);
INSERT INTO `tn_group_rule` VALUES (2, 2);
INSERT INTO `tn_group_rule` VALUES (2, 20);
INSERT INTO `tn_group_rule` VALUES (2, 7);
INSERT INTO `tn_group_rule` VALUES (2, 33);
INSERT INTO `tn_group_rule` VALUES (2, 34);
INSERT INTO `tn_group_rule` VALUES (2, 35);
INSERT INTO `tn_group_rule` VALUES (2, 36);
INSERT INTO `tn_group_rule` VALUES (2, 37);
INSERT INTO `tn_group_rule` VALUES (2, 38);
INSERT INTO `tn_group_rule` VALUES (2, 39);
INSERT INTO `tn_group_rule` VALUES (1, 66);
INSERT INTO `tn_group_rule` VALUES (1, 67);
INSERT INTO `tn_group_rule` VALUES (1, 68);
INSERT INTO `tn_group_rule` VALUES (1, 71);
INSERT INTO `tn_group_rule` VALUES (1, 72);
INSERT INTO `tn_group_rule` VALUES (1, 73);
INSERT INTO `tn_group_rule` VALUES (1, 74);
INSERT INTO `tn_group_rule` VALUES (1, 75);
INSERT INTO `tn_group_rule` VALUES (1, 76);
INSERT INTO `tn_group_rule` VALUES (1, 77);
INSERT INTO `tn_group_rule` VALUES (1, 78);
INSERT INTO `tn_group_rule` VALUES (1, 79);
INSERT INTO `tn_group_rule` VALUES (1, 80);

-- ----------------------------
-- Table structure for tn_message
-- ----------------------------
DROP TABLE IF EXISTS `tn_message`;
CREATE TABLE `tn_message`  (
  `m_id` int(11) NOT NULL AUTO_INCREMENT,
  `from_uid` int(255) NOT NULL,
  `to_uid` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`m_id`) USING BTREE,
  UNIQUE INDEX `from_uid`(`from_uid`, `to_uid`, `post_id`, `type`, `content`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 46 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tn_post
-- ----------------------------
DROP TABLE IF EXISTS `tn_post`;
CREATE TABLE `tn_post`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `discuss_id` int(11) DEFAULT 0,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `media` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `read_count` int(255) DEFAULT 0,
  `create_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 396 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tn_post_collection
-- ----------------------------
DROP TABLE IF EXISTS `tn_post_collection`;
CREATE TABLE `tn_post_collection`  (
  `uid` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tn_post_fabulous
-- ----------------------------
DROP TABLE IF EXISTS `tn_post_fabulous`;
CREATE TABLE `tn_post_fabulous`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tn_rule
-- ----------------------------
DROP TABLE IF EXISTS `tn_rule`;
CREATE TABLE `tn_rule`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(2) DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `menu` int(2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 81 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tn_rule
-- ----------------------------
INSERT INTO `tn_rule` VALUES (1, 0, '&#xe614;', ' ', '系统设置', 0);
INSERT INTO `tn_rule` VALUES (2, 0, '&#xe66f;', '', '会员管理', 0);
INSERT INTO `tn_rule` VALUES (3, 0, '&#xe655;', '', '内容管理', 0);
INSERT INTO `tn_rule` VALUES (11, 2, NULL, 'admin/user/delGroup', '删除用户组', 1);
INSERT INTO `tn_rule` VALUES (12, 2, NULL, 'admin/user/addRole', '添加角色', 1);
INSERT INTO `tn_rule` VALUES (13, 2, NULL, 'admin/user/groupRule', '用户组权限详情', 1);
INSERT INTO `tn_rule` VALUES (15, 2, NULL, 'admin/user/userList', '会员列表', 0);
INSERT INTO `tn_rule` VALUES (16, 2, NULL, 'admin/user/saveRule', '修改权限', 1);
INSERT INTO `tn_rule` VALUES (17, 2, NULL, 'admin/user/userAdd', '添加用户', 1);
INSERT INTO `tn_rule` VALUES (18, 2, NULL, 'admin/user/delUser', '删除用户', 1);
INSERT INTO `tn_rule` VALUES (21, 2, NULL, 'admin/user/userStatus', '修改用户状态', 1);
INSERT INTO `tn_rule` VALUES (22, 8, NULL, 'admin/index/welcome', '用户欢迎页', 1);
INSERT INTO `tn_rule` VALUES (32, 2, NULL, 'admin/user/adminRole', '用户组', 0);
INSERT INTO `tn_rule` VALUES (65, 1, NULL, 'admin/miniapp/baseSet', '微信小程序', 0);
INSERT INTO `tn_rule` VALUES (67, 3, NULL, 'admin/post/postList', '帖子列表', 0);
INSERT INTO `tn_rule` VALUES (68, 3, NULL, 'admin/post/delPost', '删除帖子', 1);
INSERT INTO `tn_rule` VALUES (71, 1, NULL, 'admin/system/Basics', '上传方式', 0);
INSERT INTO `tn_rule` VALUES (72, 1, NULL, 'admin/system/uploadType', '上传方式-保存', 1);
INSERT INTO `tn_rule` VALUES (73, 3, NULL, 'admin/topic/cate', '圈子类别', 0);
INSERT INTO `tn_rule` VALUES (74, 3, NULL, 'admin/topic/cateEdit', '圈子类别-编辑', 1);
INSERT INTO `tn_rule` VALUES (75, 3, NULL, 'admin/topic/cateDel', '圈子类别-删除', 1);
INSERT INTO `tn_rule` VALUES (76, 3, NULL, 'admin/topic/cateAdd', '圈子类别-添加', 1);
INSERT INTO `tn_rule` VALUES (77, 3, NULL, 'admin/topic/list', '圈子列表', 0);
INSERT INTO `tn_rule` VALUES (78, 3, NULL, 'admin/topic/topicDel', '删除圈子', 1);
INSERT INTO `tn_rule` VALUES (79, 3, NULL, 'admin/discuss/list', '话题列表', 0);
INSERT INTO `tn_rule` VALUES (80, 3, NULL, 'admin/discuss/del', '话题删除', 1);

-- ----------------------------
-- Table structure for tn_system
-- ----------------------------
DROP TABLE IF EXISTS `tn_system`;
CREATE TABLE `tn_system`  (
  `config` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `extend` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `intro` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`config`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tn_system
-- ----------------------------
INSERT INTO `tn_system` VALUES ('miniapp', '', '', NULL);
INSERT INTO `tn_system` VALUES ('uploadType', '1', '{\"endpoint\":\"https:\\/\\/oss.tn721.cn\",\"OssName\":\"\",\"accessKeyId\":\"\",\"accessKeySecret\":\"\"}', '');

-- ----------------------------
-- Table structure for tn_topic
-- ----------------------------
DROP TABLE IF EXISTS `tn_topic`;
CREATE TABLE `tn_topic`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL,
  `topic_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `cover_image` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
  `bg_image` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tn_topic
-- ----------------------------
INSERT INTO `tn_topic` VALUES (1, 170, 1, '文案馆', '描述心情的文字', '/static/images/api/topic_default/wenan.jpg', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (2, 170, 4, '征稿启事', '一个文字交易的圈子', '/static/images/api/topic_default/xztg.jpg', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (3, 170, 4, '萌宠聚集地', '一个萌宠聚集地的圈子', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (4, 170, 5, '关于吃货的尊严', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (5, 170, 5, '穿衣搭配', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (6, 170, 5, '美食圈', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (7, 170, 6, '今日歌单', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (8, 170, 6, '旅途风景', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (9, 170, 6, '网易云热评', '分享网易云音乐那些感人评论', '/static/images/api/topic_default/music.png', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (10, 170, 6, '每天为生活拍张照', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (11, 170, 6, '那个叫学校的地方', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (12, 170, 2, '随拍摄影', '', '/static/images/api/topic_default/photography.png', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (13, 170, 3, '神回复', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (14, 170, 3, '高兴段子', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (15, 170, 3, '思想驿站', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (16, 170, 3, '手机摄影', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (17, 170, 3, '电影客栈', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (18, 170, 3, '值得一看的风景', '', '', NULL, 1591212587);
INSERT INTO `tn_topic` VALUES (23, 239, 5, '美妙的时候', '', 'https://oss.tn721.cn/20200513158934914882045.jpg', NULL, 1589349165);

-- ----------------------------
-- Table structure for tn_topic_cate
-- ----------------------------
DROP TABLE IF EXISTS `tn_topic_cate`;
CREATE TABLE `tn_topic_cate`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tn_topic_cate
-- ----------------------------
INSERT INTO `tn_topic_cate` VALUES (1, '情感');
INSERT INTO `tn_topic_cate` VALUES (2, '摄影');
INSERT INTO `tn_topic_cate` VALUES (3, '校园');
INSERT INTO `tn_topic_cate` VALUES (4, '头像');
INSERT INTO `tn_topic_cate` VALUES (5, '爱好');

-- ----------------------------
-- Table structure for tn_user
-- ----------------------------
DROP TABLE IF EXISTS `tn_user`;
CREATE TABLE `tn_user`  (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `group_id` int(11) NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `gender` int(1) NOT NULL DEFAULT 0,
  `province` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
  `city` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
  `openid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(10) NOT NULL DEFAULT 0,
  `intro` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '这个人很懒，没留下什么',
  `integral` int(255) DEFAULT 0,
  `last_login_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `create_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tn_user
-- ----------------------------
INSERT INTO `tn_user` VALUES (1, 'mmteen@sina.com', 'f327551ecb53797030fd67b15d397411', 1, NULL, 0, '', '', NULL, 0, '这个人很懒，没留下什么', 0, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for tn_user_discuss
-- ----------------------------
DROP TABLE IF EXISTS `tn_user_discuss`;
CREATE TABLE `tn_user_discuss`  (
  `uid` int(11) NOT NULL,
  `discuss_id` int(255) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tn_user_topic
-- ----------------------------
DROP TABLE IF EXISTS `tn_user_topic`;
CREATE TABLE `tn_user_topic`  (
  `uid` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  UNIQUE INDEX `uid`(`uid`, `topic_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
