/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : admin_prontv

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-01-11 08:49:18
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of codes
-- ----------------------------
INSERT INTO `codes` VALUES ('1', 'rctd-034', '2018-01-10 23:21:04');
INSERT INTO `codes` VALUES ('2', 'JUY-349', '2018-01-11 00:25:27');
INSERT INTO `codes` VALUES ('3', 'asdas', '2018-01-11 00:57:36');

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
  `title` varchar(100) DEFAULT NULL,
  `host` varchar(100) DEFAULT NULL,
  `domain` varchar(100) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `quality` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `code_id` int(11) DEFAULT NULL,
  `code_value` varchar(100) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `link` varchar(100) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`,`host`,`domain`,`language`,`size`,`quality`,`link`,`source`,`code_id`,`code_value`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of videos
-- ----------------------------
INSERT INTO `videos` VALUES ('1', 'FHD juy-349 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア | Watch Free HD JAV Online', 'openload.co', 'javhdonline.com', 'en', '1.23 GB', '', '2018-01-06', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://javhdonline.com/watch/fhd-juy-349-%e5%a4%ab%e3%81%ae%e9%81%ba%e5%bd%b1%e3%81%ae%e5%89%8d%e3%8', 'https://openload.co/embed/WgU769tYsoc/FHD-juy-349.mp4');
INSERT INTO `videos` VALUES ('2', '[JUY-349] Tia – I Was Raped In Front Of My Late Husband&#039;s Picture, And I Came So Hard I Lost My', 'openload.co', 'jav68.co', 'en', '634.08 MB', '', '2018-01-03', '0', 'JUY-349', '2018-01-11 01:55:46', 'https://jav68.co/watch/juy-349-tia-i-was-raped-in-front-of-my-late-husbands-picture-and-i-came-so-ha', 'https://openload.co/embed/ZfUQwDXQUkw');
INSERT INTO `videos` VALUES ('3', 'FHD-juy-349.mp4 - Openload (72ez5Rav_oU) - DL8X', 'openload.co', 'dl8x.com', 'en', '', '', '2018-01-09', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://www.dl8x.com/watch/openload.co/f/72ez5Rav_oU/', 'https://openload.co/embed/72ez5Rav_oU/');
INSERT INTO `videos` VALUES ('4', 'JUY-349 - ティア - 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア | 免費最新最正JAV線上觀看 | 給我JAV', 'openload.co', 'givemejav.com', 'en', '', '', '2018-01-10', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://www.givemejav.com/play/MgOjQfY7/juy-349-i-was-raped-in-front-of-my-late-husbands-picture-and-', 'https://openload.co/embed/lgGgWtMpyTo');
INSERT INTO `videos` VALUES ('5', 'JUY-349 - ティア - 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア | 免費最新最正JAV線上觀看 | 給我JAV', 'openload.co', 'givemejav.com', 'en', '', '', '2018-01-10', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://www.givemejav.com/play/MgOjQfY7/juy-349-i-was-raped-in-front-of-my-late-husbands-picture-and-', 'https://openload.co/embed/lgGgWtMpyTo/');
INSERT INTO `videos` VALUES ('6', 'JUY-349 I Was Caught In Front Of My Husband &#8216;s Portrait, Caught Me Crazy. Tia &#8211; JavLoad', 'openload.co', 'javload.info', 'en', '', '', '2018-01-06', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://javload.info/40793/', 'https://openload.co/embed/QH0_XUFLTlI/');
INSERT INTO `videos` VALUES ('7', 'JUY-349 I Was Caught In Front Of My Husband &#8216;s Portrait, Caught Me Crazy. Tia &#8211; JavLoad', 'openload.co', 'javload.info', 'en', '634.08 MB', '', '2018-01-06', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://javload.info/40793/', 'https://openload.co/embed/gh8TA-xoDD8/');
INSERT INTO `videos` VALUES ('8', 'JUY-349 - Tia - I Was Raped In Front Of My Late Husband&#39;s Picture, And I Came So Hard I Lost My ', 'openload.co', 'happyjav.com', 'en', '', '', '2018-01-09', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://www.happyjav.com/watch/rSqMiO4i/juy-349-i-was-raped-in-front-of-my-late-husbands-picture-and-', 'https://openload.co/embed/cLY49jFS_DA/');
INSERT INTO `videos` VALUES ('9', 'JUY-349 - Tia - I Was Raped In Front Of My Late Husband&#39;s Picture, And I Came So Hard I Lost My ', 'openload.co', 'happyjav.com', 'en', '', '', '2018-01-09', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://www.happyjav.com/watch/rSqMiO4i/juy-349-i-was-raped-in-front-of-my-late-husbands-picture-and-', 'https://openload.co/embed/cLY49jFS_DA');
INSERT INTO `videos` VALUES ('10', 'JUY-349 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア | JAVLINKS.COM', 'openload.co', 'javlinks.com', 'en', '634.08 MB', '', '2017-12-30', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://javlinks.com/2017/12/185842/', 'https://openload.co/embed/qnW3iofW71k/JUY-349.mp4');
INSERT INTO `videos` VALUES ('11', 'JUY-349 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア - Japanese Porn Videos', 'openload.co', 'jpornvideo.com', 'en', '634.08 MB', '', '2017-12-30', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://jpornvideo.com/juy-349-%e5%a4%ab%e3%81%ae%e9%81%ba%e5%bd%b1%e3%81%ae%e5%89%8d%e3%81%a7%e7%8a%', 'https://openload.co/embed/q6LAilxim-4/JUY-349.mp4');
INSERT INTO `videos` VALUES ('12', 'JUY-349 I Was Caught In Front Of My Husband &#039;s Portrait, Caught Me Crazy. Tia - 18+ Video Strea', 'rapidvideo.com', '18streams.net', 'en', '', '', '2017-12-30', '0', 'JUY-349', '2018-01-11 01:55:46', 'https://18streams.net/juy-349/', 'https://www.rapidvideo.com/e/FMYZY4VH5U');
INSERT INTO `videos` VALUES ('13', 'JUY-349 I Was Caught In Front Of My Husband ’s Portrait, Caught Me Crazy. Tia - JAVPool.Com', 'openload.co', 'javpool.com', 'en', '', '', '2017-12-31', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://javpool.com/juy-349/', 'https://openload.co/embed/rHFGQ9k58Mw/JUY-349.mp4');
INSERT INTO `videos` VALUES ('14', 'JUY-349 I Was Caught In Front Of My Husband &#8216;s Portrait, Caught Me Crazy. Tia &#8211; My Jav L', 'openload.co', 'myjavlibrary.net', 'en', '', '', '2017-12-30', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://myjavlibrary.net/juy-349-i-was-caught-in-front-of-my-husband-s-portrait-caught-me-crazy-tia/', 'https://openload.co/embed/OucXhUVSSvs/JUY-349.mp4');
INSERT INTO `videos` VALUES ('15', 'JUY-349 I Was Caught In Front Of My Husband ’s Portrait, Caught Me Crazy. Tia - JAVLovers.Club', 'openload.co', 'javlovers.club', 'en', '', '', '2017-12-31', '0', 'JUY-349', '2018-01-11 01:55:46', 'https://javlovers.club/2017/12/31/juy-349/', 'https://openload.co/embed/xjgYiFvNnp0/');
INSERT INTO `videos` VALUES ('16', 'JUY-349 I Was Caught In Front Of My Husband ’s Portrait, Caught Me Crazy. Tia - JAVPool.Com', 'openload.co', 'javpool.com', 'en', '', '', '2017-12-31', '0', 'JUY-349', '2018-01-11 01:55:46', 'http://javpool.com/juy-349/', 'https://openload.co/embed/rHFGQ9k58Mw/');
INSERT INTO `videos` VALUES ('17', 'JUY-349 I Was Caught In Front Of My Husband &#039;s Portrait, Caught Me Crazy. Tia &ndash; NMPORN', 'rapidvideo.com', 'nmporn.com', 'en', '', '', '2017-12-31', '0', 'JUY-349', '2018-01-11 01:55:46', 'https://nmporn.com/video/juy-349-caught-front-husband-s-portrait-caught-crazy-tia/', 'https://www.rapidvideo.com/e/FMZ9CDZNQ9');
INSERT INTO `videos` VALUES ('18', 'JUY-349 I Was Caught In Front Of My Husband ’s Portrait, Caught Me Crazy. Tia - JAVLovers.Club', 'openload.co', 'javlovers.club', 'en', '', '', '2017-12-31', '0', 'JUY-349', '2018-01-11 01:55:46', 'https://javlovers.club/2017/12/31/juy-349/', 'https://openload.co/embed/xjgYiFvNnp0/JUY-349.mp4');
INSERT INTO `videos` VALUES ('19', 'Bitporno', 'bitporno.com', 'bitporno.com', 'en', '', '', '2018-01-01', '0', 'JUY-349', '2018-01-11 01:55:46', 'https://www.bitporno.com', 'https://www.bitporno.com/?v=FN0OFEQ3QF');
INSERT INTO `videos` VALUES ('20', 'JUY-349 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア - Free Watch JAV Online - Openloadダウンロード保存', 'openload.co', 'openload-d.co', 'en', '', '', '2018-01-01', '0', 'JUY-349', '2018-01-11 01:55:46', 'https://openload-d.co/f/tM5ko1heo8o/', 'https://openload.co/embed/tM5ko1heo8o/');
INSERT INTO `videos` VALUES ('30', 'JUY-349 I Was Caught In Front Of My Husband s Portrait, Caught Me Crazy. Tia - Javhub - Watch online', 'openload.co', 'javhub.net', 'en', '1.23 GB', '', '2018-01-10', '0', 'JUY-349', '2018-01-11 01:57:44', 'http://javhub.net/play/d41eefcde/5a557ca79e7e9154de83f883/JUY-349-I-Was-Caught-In-Front-Of-My-Husban', 'https://openload.co/embed/hq7vAEOp_fk');
INSERT INTO `videos` VALUES ('31', 'JUY-349 I Was Caught In Front Of My Husband s Portrait, Caught Me Crazy. Tia - Javhub - Watch online', 'openload.co', 'javhub.net', 'en', '1.23 GB', '', '2018-01-10', '0', 'JUY-349', '2018-01-11 01:57:44', 'http://javhub.net/play/d41eefcde/5a557ca79e7e9154de83f883/JUY-349-I-Was-Caught-In-Front-Of-My-Husban', 'https://openload.co/embed/hq7vAEOp_fk/');
INSERT INTO `videos` VALUES ('41', 'RCTD-034 &#8211; Polo with Harenchi swimsuit! Stack out out opportunity is 12 times, prize money 100', 'bitporno.com', 'javrave.com', 'en', '', '', '2017-10-14', '0', 'RCTD-034', '2018-01-11 02:04:52', 'http://javrave.com/rctd-034-polo-with-harenchi-swimsuit-stack-out-out-opportunity-is-12-times-prize-', 'https://www.bitporno.com/?v=FKFX3R99XC');
INSERT INTO `videos` VALUES ('42', '[RCTD-034] Unknown – Sexy Swimsuits Nip Slips! Strikeout! | JAV1080.COM', 'openload.co', 'jav1080.com', 'en', '930.91 MB', '', '2017-11-29', '0', 'RCTD-034', '2018-01-11 02:04:52', 'http://jav1080.com/movies/rctd-034-unknown-sexy-swimsuits-nip-slips-strikeout/', 'https://openload.co/embed/h2Koq5ZHPew');
INSERT INTO `videos` VALUES ('43', 'RCTD-034 Polo With Harenchi Swimsuit!Stack Out - Javhub - Watch online porn streaming for free', 'openload.co', 'javhub.net', 'en', '862.47 MB', '', '2018-01-07', '0', 'RCTD-034', '2018-01-11 02:04:52', 'http://javhub.net/play/f8176c0c9/5a52401b9e7e911d6e7865ca/RCTD-034-Polo-With-Harenchi-Swimsuit!Stack', 'https://openload.co/embed/DD86iihwWO4/');
INSERT INTO `videos` VALUES ('44', 'Posts by freaiop - JAVLibrary', 'openload.co', 'javlibrary.com', 'en', '930.91 MB', '', '2017-10-17', '0', 'RCTD-034', '2018-01-11 02:04:52', 'http://www.javlibrary.com/en/userposts.php?mode=&u=freaiop&page=23', 'https://openload.co/embed/wodfMgtVC1c/');
INSERT INTO `videos` VALUES ('45', 'RCTD-034 - Unknown - Sexy Swimsuits Nip Slips! Strikeout! |    Free JAV Streaming | BABY Jav', 'openload.co', 'babyjav.com', 'en', '', '', '2017-12-04', '0', 'RCTD-034', '2018-01-11 02:04:52', 'http://www.babyjav.com/play/Y6G88F0U/rctd-034-sexy-swimsuits-nip-slips-strikeout/', 'https://openload.co/embed/8OksOBkkVok');
INSERT INTO `videos` VALUES ('46', 'RCTD-034 - Unknown - Sexy Swimsuits Nip Slips! Strikeout! |    Free JAV Streaming | BABY Jav', 'openload.co', 'babyjav.com', 'en', '', '', '2017-12-04', '0', 'RCTD-034', '2018-01-11 02:04:52', 'http://www.babyjav.com/play/Y6G88F0U/rctd-034-sexy-swimsuits-nip-slips-strikeout/', 'https://openload.co/embed/8OksOBkkVok/');
INSERT INTO `videos` VALUES ('47', 'RCTD-034 - Unknown - Sexy Swimsuits Nip Slips! Strikeout! | Free Jav Online Streaming Watch Now ~ Ha', 'openload.co', 'happyjav.com', 'en', '', '', '2017-12-06', '0', 'RCTD-034', '2018-01-11 02:04:52', 'http://www.happyjav.com/watch/IFnBsmod/rctd-034-sexy-swimsuits-nip-slips-strikeout/', 'https://openload.co/embed/-NU2_UPwLw0');
INSERT INTO `videos` VALUES ('48', 'RCTD-034 - 不明 - ハレンチ水着でポロリ！ストラックアウト チャンスは12回、パネルを1枚抜く度に賞金10万円！3枚以下なら見知らぬチ○ポを抜いてもらうエロ罰ゲーム！！ |    免費最新', 'openload.co', 'givemejav.com', 'en', '', '', '2017-12-05', '0', 'RCTD-034', '2018-01-11 02:04:52', 'http://www.givemejav.com/play/C7jmRIYq/rctd-034-sexy-swimsuits-nip-slips-strikeout/', 'https://openload.co/embed/_aSFaCbNtEE/');
INSERT INTO `videos` VALUES ('49', 'RCTD-034 Polo With Harenchi Swimsuit!Stack Out - JAVLovers.Club', 'openload.co', 'javlovers.club', 'en', '', '', '2017-12-14', '0', 'RCTD-034', '2018-01-11 02:04:52', 'https://javlovers.club/2017/10/10/rctd-034/', 'https://openload.co/embed/1AdmgDSIfb8/');
INSERT INTO `videos` VALUES ('50', 'Posts by malakias9999 - JAVLibrary', 'openload.co', 'javlibrary.com', 'en', '930.91 MB', '', '2017-12-14', '0', 'RCTD-034', '2018-01-11 02:04:52', 'http://www.javlibrary.com/en/userposts.php?mode=&u=malakias9999&page=13', 'https://openload.co/embed/u9pdivv6QVg/');
