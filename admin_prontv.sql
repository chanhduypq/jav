/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : admin_prontv

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-01-12 23:07:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for codes
-- ----------------------------
DROP TABLE IF EXISTS `codes`;
CREATE TABLE `codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(1000) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of codes
-- ----------------------------
INSERT INTO `codes` VALUES ('4', 'dfgdfgdfgdfgdfgdfg', '2018-01-11 17:04:54');

-- ----------------------------
-- Table structure for sites
-- ----------------------------
DROP TABLE IF EXISTS `sites`;
CREATE TABLE `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `search_parameter` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `search_result_parameter` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `detail_parameter` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `video_parameter` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `video_host` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_parameter` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of sites
-- ----------------------------
INSERT INTO `sites` VALUES ('3', 'javdoe', 'https://www.javdoe.com', '/search/movie/[dvdcode].html', '.v-desc a', '/movie/', 'iframe#avcms_player', 'openload', '.info-box-heading', null);
INSERT INTO `sites` VALUES ('4', 'letsjav', 'http://www.letsjav.com', '/en/?q=[dvdcode]', '.thumbnail-a', '/en/watch/', 'iframe.embed-responsive-item', 'openload', '.well-row1-col1', null);
INSERT INTO `sites` VALUES ('5', 'javhub', 'http://javhub.net', '/search/[dvdcode]', '.item-title a.title', '/play/', 'iframe.embed-player', 'openload', 'li.play_movie_title', null);

-- ----------------------------
-- Table structure for videos
-- ----------------------------
DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `host` varchar(50) DEFAULT NULL,
  `domain` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `quality` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `code_id` int(11) DEFAULT NULL,
  `code_value` varchar(50) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `link` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`,`host`,`domain`,`language`,`size`,`quality`,`link`,`source`,`code_id`,`code_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of videos
-- ----------------------------
