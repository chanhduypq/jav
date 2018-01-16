/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : admin_prontv

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-01-16 08:54:45
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of codes
-- ----------------------------
INSERT INTO `codes` VALUES ('5', 'rctd-034', '2018-01-13 22:31:06');
INSERT INTO `codes` VALUES ('6', 'CESD-502', '2018-01-13 22:31:17');
INSERT INTO `codes` VALUES ('7', 'JUY-349', '2018-01-13 22:31:26');
INSERT INTO `codes` VALUES ('8', 'bkd-24', '2018-01-13 22:31:36');

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
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of videos
-- ----------------------------
INSERT INTO `videos` VALUES ('1', 'RCTD-034 &#8211; Polo with Harenchi swimsuit! Stack out out opportunity is 12 times, prize money 100,000 yen every time you pull out one panel! An erotic punishment game to have an unfamiliar tip pull', 'bitporno.com', 'javrave.com', 'en', '', '', '2017-10-14', '5', 'rctd-034', '2018-01-15 16:22:17', 'http://javrave.com/rctd-034-polo-with-harenchi-swimsuit-stack-out-out-opportunity-is-12-times-prize-money-100000-yen-every-time-you-pull-out-one-panel-an-erotic-punishment-game-to-have-an-unfamiliar-tip-pull-out-i', 'https://www.bitporno.com/?v=FKFX3R99XC');
INSERT INTO `videos` VALUES ('2', '[RCTD-034] Unknown – Sexy Swimsuits Nip Slips! Strikeout! | JAV1080.COM', 'openload.co', 'jav1080.com', 'en', '930.91 MB', '', '2017-11-29', '5', 'rctd-034', '2018-01-15 16:22:17', 'http://jav1080.com/movies/rctd-034-unknown-sexy-swimsuits-nip-slips-strikeout', 'https://openload.co/embed/h2Koq5ZHPew');
INSERT INTO `videos` VALUES ('3', 'RCTD-034 Polo With Harenchi Swimsuit!Stack Out - Javhub - Watch online porn streaming for free', 'openload.co', 'javhub.net', 'en', '862.47 MB', '', '2018-01-07', '5', 'rctd-034', '2018-01-15 16:22:17', 'http://javhub.net/play/f8176c0c9/5a52401b9e7e911d6e7865ca/RCTD-034-Polo-With-Harenchi-Swimsuit!Stack-Out', 'https://openload.co/embed/DD86iihwWO4');
INSERT INTO `videos` VALUES ('4', 'Posts by freaiop - JAVLibrary', 'openload.co', 'javlibrary.com', 'en', '930.91 MB', '', '2017-10-17', '5', 'rctd-034', '2018-01-15 16:22:17', 'http://www.javlibrary.com/en/userposts.php?mode=&u=freaiop&page=23', 'https://openload.co/embed/wodfMgtVC1c');
INSERT INTO `videos` VALUES ('5', 'RCTD-034 - Unknown - Sexy Swimsuits Nip Slips! Strikeout! |    Free JAV Streaming | BABY Jav', 'openload.co', 'babyjav.com', 'en', '', '', '2017-12-04', '5', 'rctd-034', '2018-01-15 16:22:17', 'http://www.babyjav.com/play/Y6G88F0U/rctd-034-sexy-swimsuits-nip-slips-strikeout', 'https://openload.co/embed/8OksOBkkVok');
INSERT INTO `videos` VALUES ('7', 'RCTD-034 - Unknown - Sexy Swimsuits Nip Slips! Strikeout! | Free Jav Online Streaming Watch Now ~ Happy Jav ~', 'openload.co', 'happyjav.com', 'en', '', '', '2017-12-06', '5', 'rctd-034', '2018-01-15 16:22:17', 'http://www.happyjav.com/watch/IFnBsmod/rctd-034-sexy-swimsuits-nip-slips-strikeout', 'https://openload.co/embed/-NU2_UPwLw0');
INSERT INTO `videos` VALUES ('8', 'RCTD-034 - 不明 - ハレンチ水着でポロリ！ストラックアウト チャンスは12回、パネルを1枚抜く度に賞金10万円！3枚以下なら見知らぬチ○ポを抜いてもらうエロ罰ゲーム！！ |    免費最新最正JAV線上觀看 | 給我JAV', 'openload.co', 'givemejav.com', 'en', '', '', '2017-12-05', '5', 'rctd-034', '2018-01-15 16:22:17', 'http://www.givemejav.com/play/C7jmRIYq/rctd-034-sexy-swimsuits-nip-slips-strikeout', 'https://openload.co/embed/_aSFaCbNtEE');
INSERT INTO `videos` VALUES ('9', 'RCTD-034 Polo With Harenchi Swimsuit!Stack Out - JAVLovers.Club', 'openload.co', 'javlovers.club', 'en', '', '', '2017-12-14', '5', 'rctd-034', '2018-01-15 16:22:17', 'https://javlovers.club/2017/10/10/rctd-034', 'https://openload.co/embed/1AdmgDSIfb8');
INSERT INTO `videos` VALUES ('10', 'Posts by malakias9999 - JAVLibrary', 'openload.co', 'javlibrary.com', 'en', '930.91 MB', '', '2017-12-14', '5', 'rctd-034', '2018-01-15 16:22:17', 'http://www.javlibrary.com/en/userposts.php?mode=&u=malakias9999&page=13', 'https://openload.co/embed/u9pdivv6QVg');
INSERT INTO `videos` VALUES ('11', 'Posts by javview - JAVLibrary', 'vidoza.net', 'javlibrary.com', 'en', '1.36 GB', '', '2018-01-03', '6', 'CESD-502', '2018-01-15 16:22:17', 'http://www.javlibrary.com/en/userposts.php?mode=&u=javview&page=2', 'https://vidoza.net/oqdc2i2ge4rk.html');
INSERT INTO `videos` VALUES ('12', '[CESD-502] Kagami Shizuka – Creampied Busty Mother-In-Law Who Became Her Son&#039;s Submissive Slave Shizuka Kagami | JAV68 | Free JAV Streaming | Japanese Porn Tubes Online HD', 'openload.co', 'jav68.co', 'en', '843.36 MB', '', '2018-01-04', '6', 'CESD-502', '2018-01-15 16:22:17', 'https://jav68.co/watch/cesd-502-kagami-shizuka-creampied-busty-mother-in-law-who-became-her-sons-submissive-slave-shizuka-kagami', 'https://openload.co/embed/TtFMkvcvSis');
INSERT INTO `videos` VALUES ('13', 'CESD-502 My Son &#039;s Slave Became Slaughter Breast Breast Mother - In - Law Creative Vaginal Cum Shot Kagami Quietly - 18+ Video Streaming', 'rapidvideo.com', '18streams.net', 'en', '', '', '2017-12-28', '6', 'CESD-502', '2018-01-15 16:22:17', 'https://18streams.net/cesd-502-my-son-s-slave-became-slaughter-breast-breast-mother-in-law-creative-vaginal-cum-shot-kagami-quietly', 'https://www.rapidvideo.com/e/FMVU6LLYTE');
INSERT INTO `videos` VALUES ('14', 'CESD-502 - Kagami Shizuka - Creampied Busty Mother-In-Law Who Became Her Son&#39;s Submissive Slave Shizuka Kagami | Free Jav Online Streaming Watch Now ~ Happy Jav ~', 'openload.co', 'happyjav.com', 'en', '', '', '2018-01-09', '6', 'CESD-502', '2018-01-15 16:22:17', 'http://www.happyjav.com/watch/0oJ1HXEq/cesd-502-creampied-busty-mother-in-law-who-became-her-sons-submissive-slave-shizuka-kagami', 'https://openload.co/embed/U9vT7ImDWoI');
INSERT INTO `videos` VALUES ('15', 'Openloadvideo', 'openload.co', 'openloadvideo.com', 'en', '', '', '2018-01-11', '6', 'CESD-502', '2018-01-15 16:22:17', 'http://www.openloadvideo.com/show?sourcetitle=CESD-502+My+Son+%26%23039%3Bs+Slave+Became+Slaughter+Breast+Breast+Mother+-+In+-+Law+Creative+Vaginal+Cum+Shot+Kagami+Quietly&url=https%3A%2F%2Fopenload.co%2Ff%2FdiqZiI632nI', 'https://openload.co/embed/diqZiI632nI');
INSERT INTO `videos` VALUES ('16', 'CESD-502 My Son &#039;s Slave Became Slaughter Breast Breast Mother - In - Law Creative Vaginal Cum Shot Kagami Quietly &ndash; NMPORN', 'rapidvideo.com', 'nmporn.com', 'en', '', '', '2017-12-29', '6', 'CESD-502', '2018-01-15 16:22:17', 'https://nmporn.com/video/cesd-502-son-s-slave-became-slaughter-breast-breast-mother-law-creative-vaginal-cum-shot-kagami-quietly', 'https://www.rapidvideo.com/e/FMWRFCJLH9');
INSERT INTO `videos` VALUES ('17', 'CESD-502  My Son &#8216;s Slave Became Slaughter Breast Breast Mother &#8211; In &#8211; Law Creative Vaginal Cum Shot Kagami Quietly &#8211; My Jav Library . Net', 'openload.co', 'myjavlibrary.net', 'en', '', '', '2017-12-28', '6', 'CESD-502', '2018-01-15 16:22:17', 'http://myjavlibrary.net/cesd-502-my-son-s-slave-became-slaughter-breast-breast-mother-in-law-creative-vaginal-cum-shot-kagami-quietly', 'https://openload.co/embed/zufzWGhL8kg/CESD-502.mp4');
INSERT INTO `videos` VALUES ('18', 'CESD-502 - 加賀美しずか - 息子の言いなり奴隷になった爆乳義母生中出し 加賀美しずか |    免費最新最正JAV線上觀看 | 給我JAV', 'openload.co', 'givemejav.com', 'en', '', '', '2018-01-12', '6', 'CESD-502', '2018-01-15 16:22:17', 'http://www.givemejav.com/play/3KqSyChd/cesd-502-creampied-busty-mother-in-law-who-became-her-sons-submissive-slave-shizuka-kagami', 'https://openload.co/embed/wAn4JHuu1iE');
INSERT INTO `videos` VALUES ('20', 'CESD-502 My Son &#039;s Slave Became Slaughter Breast Breast Mother - In - Law Creative Vaginal Cum Shot Kagami Quietly', 'openload.co', 'javjerk.com', 'en', '843.36 MB', '', '2018-01-10', '6', 'CESD-502', '2018-01-15 16:22:17', 'https://www.javjerk.com/watch/cesd-502', 'https://openload.co/embed/lsS-h9o_kPE/CESD-502.mp4');
INSERT INTO `videos` VALUES ('21', 'FHD juy-349 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア | Watch Free  HD JAV Online', 'openload.co', 'javhdonline.com', 'en', '1.23 GB', '', '2018-01-06', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://javhdonline.com/watch/fhd-juy-349-%e5%a4%ab%e3%81%ae%e9%81%ba%e5%bd%b1%e3%81%ae%e5%89%8d%e3%81%a7%e7%8a%af%e3%81%95%e3%82%8c%e3%81%a6%e3%80%81%e6%b0%97%e3%81%8c%e7%8b%82%e3%81%86%e3%81%bb%e3%81%a9%e7%b5%b6%e9%a0%82', 'https://openload.co/embed/WgU769tYsoc/FHD-juy-349.mp4');
INSERT INTO `videos` VALUES ('22', '[JUY-349] Tia – I Was Raped In Front Of My Late Husband&#039;s Picture, And I Came So Hard I Lost My Mind Tia | JAV68 | Free JAV Streaming | Japanese Porn Tubes Online HD', 'openload.co', 'jav68.co', 'en', '634.08 MB', '', '2018-01-03', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://jav68.co/watch/juy-349-tia-i-was-raped-in-front-of-my-late-husbands-picture-and-i-came-so-hard-i-lost-my-mind-tia', 'https://openload.co/embed/ZfUQwDXQUkw');
INSERT INTO `videos` VALUES ('23', 'JUY-349 I Was Raped In Front Of My Late Husband&#039;s Picture | JAV68 | Free JAV Streaming | Japanese Porn Tubes Online HD', 'openload.co', 'jav68.co', 'en', '1.23 GB', '', '2018-01-11', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://jav68.co/watch/juy-349-i-was-raped-in-front-of-my-late-husbands-picture', 'https://openload.co/embed/f0Nyt7PXW4Y/JUY-349HD.mp4');
INSERT INTO `videos` VALUES ('24', 'JUY-349 I Was Caught In Front Of My Husband &#039;s Portrait, Caught Me Crazy. Tia - 18+ Video Streaming', 'rapidvideo.com', '18streams.net', 'en', '', '', '2017-12-30', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://18streams.net/juy-349', 'https://www.rapidvideo.com/e/FMYZY4VH5U');
INSERT INTO `videos` VALUES ('25', 'JUY-349 - Tia - I Was Raped In Front Of My Late Husband&#39;s Picture, And I Came So Hard I Lost My Mind Tia |    Free JAV Streaming | BABY Jav', 'openload.co', 'babyjav.com', 'en', '', '', '2018-01-11', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://www.babyjav.com/play/omHroTIM/juy-349-i-was-raped-in-front-of-my-late-husbands-picture-and-i-came-so-hard-i-lost-my-mind-tia', 'https://openload.co/embed/bS4bHqM5AZI');
INSERT INTO `videos` VALUES ('26', 'JUY-349  I Was Caught In Front Of My Husband &#8216;s Portrait, Caught Me Crazy. Tia &#8211; My Jav Library . Net', 'openload.co', 'myjavlibrary.net', 'en', '', '', '2017-12-30', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://myjavlibrary.net/juy-349-i-was-caught-in-front-of-my-husband-s-portrait-caught-me-crazy-tia', 'https://openload.co/embed/OucXhUVSSvs/JUY-349.mp4');
INSERT INTO `videos` VALUES ('27', 'JUY-349 - ティア - 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア |    免費最新最正JAV線上觀看 | 給我JAV', 'openload.co', 'givemejav.com', 'en', '', '', '2018-01-10', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://www.givemejav.com/play/MgOjQfY7/juy-349-i-was-raped-in-front-of-my-late-husbands-picture-and-i-came-so-hard-i-lost-my-mind-tia', 'https://openload.co/embed/lgGgWtMpyTo');
INSERT INTO `videos` VALUES ('28', 'JUY-349 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア - Japanese Porn Videos', 'openload.co', 'jpornvideo.com', 'en', '634.08 MB', '', '2017-12-30', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://jpornvideo.com/juy-349-%e5%a4%ab%e3%81%ae%e9%81%ba%e5%bd%b1%e3%81%ae%e5%89%8d%e3%81%a7%e7%8a%af%e3%81%95%e3%82%8c%e3%81%a6%e3%80%81%e6%b0%97%e3%81%8c%e7%8b%82%e3%81%86%e3%81%bb%e3%81%a9%e7%b5%b6%e9%a0%82%e3%81%97-2', 'https://openload.co/embed/q6LAilxim-4/JUY-349.mp4');
INSERT INTO `videos` VALUES ('29', 'JUY-349 I Was Caught In Front Of My Husband ’s Portrait, Caught Me Crazy. Tia - JAVPool.Com', 'openload.co', 'javpool.com', 'en', '', '', '2017-12-31', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://javpool.com/juy-349', 'https://openload.co/embed/rHFGQ9k58Mw/JUY-349.mp4');
INSERT INTO `videos` VALUES ('32', 'JUY-349 I Was Caught In Front Of My Husband &#039;s Portrait, Caught Me Crazy. Tia &ndash; NMPORN', 'rapidvideo.com', 'nmporn.com', 'en', '', '', '2017-12-31', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://nmporn.com/video/juy-349-caught-front-husband-s-portrait-caught-crazy-tia', 'https://www.rapidvideo.com/e/FMZ9CDZNQ9');
INSERT INTO `videos` VALUES ('33', 'JUY-349 I Was Caught In Front Of My Husband &#8216;s Portrait, Caught Me Crazy. Tia &#8211; JavLoad', 'openload.co', 'javload.info', 'en', '', '', '2018-01-06', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://javload.info/40793', 'https://openload.co/embed/QH0_XUFLTlI');
INSERT INTO `videos` VALUES ('34', 'JUY-349 I Was Caught In Front Of My Husband ’s Portrait, Caught Me Crazy. Tia - JAVLovers.Club', 'openload.co', 'javlovers.club', 'en', '', '', '2017-12-31', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://javlovers.club/2017/12/31/juy-349', 'https://openload.co/embed/xjgYiFvNnp0/JUY-349.mp4');
INSERT INTO `videos` VALUES ('35', 'FHD-juy-349.mp4 - Openload (72ez5Rav_oU) - DL8X', 'openload.co', 'dl8x.com', 'en', '', '', '2018-01-09', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://www.dl8x.com/watch/openload.co/f/72ez5Rav_oU', 'https://openload.co/embed/72ez5Rav_oU');
INSERT INTO `videos` VALUES ('36', 'JUY-349 - Tia - I Was Raped In Front Of My Late Husband&#39;s Picture, And I Came So Hard I Lost My Mind Tia | Free Jav Online Streaming Watch Now ~ Happy Jav ~', 'openload.co', 'happyjav.com', 'en', '', '', '2018-01-09', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://www.happyjav.com/watch/rSqMiO4i/juy-349-i-was-raped-in-front-of-my-late-husbands-picture-and-i-came-so-hard-i-lost-my-mind-tia', 'https://openload.co/embed/cLY49jFS_DA');
INSERT INTO `videos` VALUES ('37', 'JUY-349 I Was Caught In Front Of My Husband ’s Portrait, Caught Me Crazy. Tia - JAVLovers.Club', 'openload.co', 'javlovers.club', 'en', '', '', '2017-12-31', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://javlovers.club/2017/12/31/juy-349', 'https://openload.co/embed/xjgYiFvNnp0');
INSERT INTO `videos` VALUES ('38', 'JUY-349 I Was Caught In Front Of My Husband ’s Portrait, Caught Me Crazy. Tia - JAVPool.Com', 'openload.co', 'javpool.com', 'en', '', '', '2017-12-31', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://javpool.com/juy-349', 'https://openload.co/embed/rHFGQ9k58Mw');
INSERT INTO `videos` VALUES ('39', 'Bitporno', 'bitporno.com', 'bitporno.com', 'en', '', '', '2018-01-01', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://www.bitporno.com', 'https://www.bitporno.com/?v=FN0OFEQ3QF');
INSERT INTO `videos` VALUES ('41', 'JUY-349 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア | JAVLINKS.COM', 'openload.co', 'javlinks.com', 'en', '634.08 MB', '', '2017-12-30', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://javlinks.com/2017/12/185842', 'https://openload.co/embed/qnW3iofW71k/JUY-349.mp4');
INSERT INTO `videos` VALUES ('42', 'JUY-349 I Was Caught In Front Of My Husband &#8216;s Portrait, Caught Me Crazy. Tia &#8211; JavLoad', 'openload.co', 'javload.info', 'en', '634.08 MB', '', '2018-01-06', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://javload.info/40793', 'https://openload.co/embed/gh8TA-xoDD8');
INSERT INTO `videos` VALUES ('43', 'JUY-349 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア - Free Watch JAV Online - Openloadダウンロード保存', 'openload.co', 'openload-d.co', 'en', '', '', '2018-01-01', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://openload-d.co/f/tM5ko1heo8o', 'https://openload.co/embed/tM5ko1heo8o');
INSERT INTO `videos` VALUES ('44', 'JUY-349 Tia - I Was Raped In Front Of My Late Husband&#39;s Picture, And I Came So Hard I Lost My Mind Tia | JavDL - watch Jav online streaming free', 'openload.co', 'javdl.co', 'en', '', '', '2018-01-11', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://www.javdl.co/1Do0JhuJOzcV/watch-jav-online-juy-349-i-was-raped-in-front-of-my-late-husbands-picture-and-i-came-so-hard-i-lost-my-mind-tia', 'https://openload.co/embed/6FzvYJd8Ll4');
INSERT INTO `videos` VALUES ('46', '[JUY-349]I Was Raped In Front Of My Late Husband | 7mm.tv-騎美眉線上TV-手機A片網-手機A片王-7mm線上AV', 'openload.co', '7mm.tv', 'en', '', '', '2017-12-29', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://7mm.tv/jmv_content_61696.html', 'https://openload.co/embed/5kWmjtHB3Pc');
INSERT INTO `videos` VALUES ('47', '[JUY-349]I Was Raped In Front Of My Late Husband | 7mm.tv-騎美眉線上TV-手機A片網-手機A片王-7mm線上AV', 'openload.co', '7mm.tv', 'en', '634.08 MB', '', '2017-12-31', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://7mm.tv/jmv_content_61696.html', 'https://openload.co/embed/qnW3iofW71k');
INSERT INTO `videos` VALUES ('48', '[JUY-349]I Was Raped In Front Of My Late Husband | 7mm.tv-騎美眉線上TV-手機A片網-手機A片王-7mm線上AV', 'openload.co', '7mm.tv', 'en', '634.08 MB', '', '2017-12-31', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://7mm.tv/jmv_content_61696.html', 'https://openload.co/embed/q6LAilxim-4');
INSERT INTO `videos` VALUES ('49', '[JUY-349]I Was Raped In Front Of My Late Husband | 7mm.tv-騎美眉線上TV-手機A片網-手機A片王-7mm線上AV', 'openload.co', '7mm.tv', 'en', '', '', '2017-12-31', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://7mm.tv/jmv_content_61696.html', 'https://openload.co/embed/OucXhUVSSvs');
INSERT INTO `videos` VALUES ('50', '[JUY-349]I Was Raped In Front Of My Late Husband | 7mm.tv-騎美眉線上TV-手機A片網-手機A片王-7mm線上AV', 'openload.co', '7mm.tv', 'en', '', '', '2017-12-30', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://7mm.tv/jmv_content_61696.html', 'https://openload.co/embed/FvjbMD0sLfg');
INSERT INTO `videos` VALUES ('51', 'JUY-349 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア | withjav', 'vidoza.net', 'withjav.com', 'en', '1.42 GB', '', '2018-01-01', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://withjav.com/juy-349-%e5%a4%ab%e3%81%ae%e9%81%ba%e5%bd%b1%e3%81%ae%e5%89%8d%e3%81%a7%e7%8a%af%e3%81%95%e3%82%8c%e3%81%a6%e3%80%81%e6%b0%97%e3%81%8c%e7%8b%82%e3%81%86%e3%81%bb%e3%81%a9%e7%b5%b6%e9%a0%82%e3%81%97', 'https://vidoza.net/xhwjtysdeudj.html');
INSERT INTO `videos` VALUES ('52', 'FHD-juy-349.mp4 - Openload (72ez5Rav_oU) - DL8X', 'openload.co', 'dl8x.com', 'en', '', '', '2018-01-09', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://www.dl8x.com/watch/openload.co/f/72ez5Rav_oU', 'https://openload.co/embed/523TbKpJmjw');
INSERT INTO `videos` VALUES ('53', 'JUY-349 I Was Raped In Front Of My Late Husband&#8217;s Picture - JAVEGG.COM', 'openload.co', 'javegg.com', 'en', '', '', '2018-01-11', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://www.javegg.com/jav/36356', 'https://openload.co/embed/U_aWwvonviA');
INSERT INTO `videos` VALUES ('54', 'JUY-349 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア | withjav', 'openload.co', 'withjav.com', 'en', '634.08 MB', '', '2018-01-01', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://withjav.com/juy-349-%e5%a4%ab%e3%81%ae%e9%81%ba%e5%bd%b1%e3%81%ae%e5%89%8d%e3%81%a7%e7%8a%af%e3%81%95%e3%82%8c%e3%81%a6%e3%80%81%e6%b0%97%e3%81%8c%e7%8b%82%e3%81%86%e3%81%bb%e3%81%a9%e7%b5%b6%e9%a0%82%e3%81%97', 'https://openload.co/embed/jzaoD6f0fNM/171229JUY-349.mp4');
INSERT INTO `videos` VALUES ('55', 'JUY-349 I Was Caught In Front Of My Husband s Portrait, Caught Me Crazy. Tia - Javhub - Watch online porn streaming for free', 'openload.co', 'javhub.net', 'en', '1.23 GB', '', '2018-01-10', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://javhub.net/play/d41eefcde/5a557ca79e7e9154de83f883/JUY-349-I-Was-Caught-In-Front-Of-My-Husband-', 'https://openload.co/embed/hq7vAEOp_fk');
INSERT INTO `videos` VALUES ('57', '【巨乳】ティア 夫を海難事故で失った私は哀しみに暮れながら初七日を迎えたそんな時同じ事故で弟を喪った川本さんがやってきて夫の責任を問い私に身体で償うように命じた抵抗する間もなく愛する夫の遺影の前で犯されその後も身体を犯され続け JUY-349 | JavPortal.net 無料AVIV動画', 'openload.co', 'javportal.net', 'ja', '', '', '2017-12-30', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://javportal.net/870656', 'https://openload.co/embed/FvjbMD0sLfg');
INSERT INTO `videos` VALUES ('58', 'Watch JUY-349 Tia JAV Uncensored - Ohyeah1080.com', 'openload.co', 'ohyeah1080.com', 'en', '634.08 MB', '', '2017-12-31', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://ohyeah1080.com/?uembed=218165', 'https://openload.co/embed/7sixaJYv5bo/SAJ123017_1332054716.mp4');
INSERT INTO `videos` VALUES ('59', '[Openload] Tuyển chọn JAV gái xinh ( cập nhật hằng ngày ) | Page 7', 'openload.co', 'thiendia.com', 'vi', '1.22 GB', '', '2018-01-15', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://thiendia.com/diendan/threads/openload-tuyen-chon-jav-gai-xinh-cap-nhat-hang-ngay.1116207/page-7', 'https://openload.co/embed/6lGGJPNmBzU');
INSERT INTO `videos` VALUES ('60', 'JUY-349 I Was Raped In Front Of My Late Husband&#039;s Picture, And I Came So Hard I Lost My Mind Tia - JavLot', 'openload.co', 'javlot.com', 'en', '634.08 MB', '', '2017-12-30', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://javlot.com/18065-juy-349.html', 'https://openload.co/embed/lFptKxxP5-0');
INSERT INTO `videos` VALUES ('61', 'Latest and hottest porn videos - HD Porn Videos - SpankBang', 'spankbang.com', 'spankbang.com', 'en', '', '', '2018-01-09', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://spankbang.com/new_videos/13', 'https://spankbang.com/1tbyr/video/juy+349');
INSERT INTO `videos` VALUES ('62', 'JUY-349 &#8211; Tia &#8211; JUY-349 I Was Caught In Front Of My Husband &#8216;s Portrait, Caught Me Crazy. Tia &#8211; Korean Porn Online', 'streamcherry.com', 'korean720.com', 'en', '', '', '2017-12-31', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://korean720.com/video/juy-349-tia-juy-349-i-was-caught-in-front-of-my-husband-s-portrait-caught-me-crazy-tia', 'https://streamcherry.com/f/aaboecplcntlnecq');
INSERT INTO `videos` VALUES ('63', 'JUY-349 夫の遺影の前で犯されて、気が狂うほど絶頂した私。 ティア | withjav', 'streamcherry.com', 'withjav.com', 'en', '', '', '2018-01-01', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://withjav.com/juy-349-%e5%a4%ab%e3%81%ae%e9%81%ba%e5%bd%b1%e3%81%ae%e5%89%8d%e3%81%a7%e7%8a%af%e3%81%95%e3%82%8c%e3%81%a6%e3%80%81%e6%b0%97%e3%81%8c%e7%8b%82%e3%81%86%e3%81%bb%e3%81%a9%e7%b5%b6%e9%a0%82%e3%81%97', 'https://streamcherry.com/f/cstomftatnkbopdl');
INSERT INTO `videos` VALUES ('64', '【巨乳】ティア 夫を海難事故で失った私は哀しみに暮れながら初七日を迎えたそんな時同じ事故で弟を喪った川本さんがやってきて夫の責任を問い私に身体で償うように命じた抵抗する間もなく愛する夫の遺影の前で犯されその後も身体を犯され続け JUY-349 | JavPortal.net 無料AVIV動画', 'streamcherry.com', 'javportal.net', 'ja', '', '', '2017-12-30', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://javportal.net/870656', 'https://streamcherry.com/f/knlkfomlpbmepsap');
INSERT INTO `videos` VALUES ('65', 'R18.com: Videos: Video On Demand: Adult Movies (19)', 'r18.com', 'r18.com', 'en', '', '', '2018-01-08', '7', 'JUY-349', '2018-01-15 16:21:42', 'http://www.r18.com/videos/vod/movies/list/pagesize=30/price=all/sort=new/type=all/page=19', 'http://www.r18.com/videos/vod/movies/detail/-/id=juy00349');
INSERT INTO `videos` VALUES ('66', 'JAV Censored, Porn Asian Censored HD | JAV FREE ONLINE 2017', 'javfinder.is', 'javfinder.is', 'en', '', '', '2018-01-08', '7', 'JUY-349', '2018-01-15 16:21:42', 'https://javfinder.is/category/censored/page-1.html', 'https://javfinder.is/movie/watch/madonna-juy-349-bokep-jav-jepang-tia-i-was-caught-in-front-of-my-husband-is-portrait-caught-me-crazy.html');
INSERT INTO `videos` VALUES ('114', 'BKD-24  Maternal And Child Copulation [path Yamanakako] &#8211; My Jav Library . Net', 'openload.co', 'myjavlibrary.net', 'en', '', '', '2017-11-29', '8', 'bkd-24', '2018-01-15 16:21:42', 'http://myjavlibrary.net/bkd-24-maternal-and-child-copulation-path-yamanakako', 'https://openload.co/embed/NZE4darlBNY/BKD-24.mp4');
INSERT INTO `videos` VALUES ('115', 'avsick_jav_24.mp4 - Openload (GmesB_ok11I) - DL8X', 'openload.co', 'dl8x.com', 'en', '', '', '2017-06-27', '8', 'bkd-24', '2018-01-15 16:21:42', 'http://www.dl8x.com/watch/openload.co/f/GmesB_ok11I', 'https://openload.co/embed/zX8PmIVUSQ0');
INSERT INTO `videos` VALUES ('116', 'Watch Online [Full Dvd] BKD-16 - Supreme Ecstasy of Cream Pie Sex | Server Vip #24 BeeJP.Net', 'openload.co', 'beejp.net', 'en', '', '', '2017-06-13', '8', 'bkd-24', '2018-01-15 16:21:42', 'http://beejp.net/watch/movie-28293/video/162766/full-dvd-bkd-16-supreme-ecstasy-of-cream-pie-sex.html', 'https://openload.co/embed/dHooyD5eCRM');
INSERT INTO `videos` VALUES ('117', 'Free Porn, Sex Movies & Porn Tube - XXX Gay Porno Videos', 'xtube.com', 'xtube.com', 'en', '', '', '2015-06-23', '8', 'bkd-24', '2018-01-15 16:21:42', 'http://www.xtube.com', 'http://www.xtube.com/watch.php?v=qZB2f-S801-');
INSERT INTO `videos` VALUES ('118', 'Free Porn, Sex Movies & Porn Tube - XXX Gay Porno Videos', 'xtube.com', 'xtube.com', 'en', '', '', '2015-07-25', '8', 'bkd-24', '2018-01-15 16:21:42', 'http://www.xtube.com', 'http://www.xtube.com/watch.php?v=EnBlS-C858-');
INSERT INTO `videos` VALUES ('119', 'Free Porn, Sex Movies & Porn Tube - XXX Gay Porno Videos', 'xtube.com', 'xtube.com', 'en', '', '', '2015-07-26', '8', 'bkd-24', '2018-01-15 16:21:42', 'http://www.xtube.com', 'http://www.xtube.com/watch.php?v=ZEoSp-C257-');
INSERT INTO `videos` VALUES ('120', 'ROCKET AV RCTD-034 Route Yamazaki Five big tits amateur challenge the erotic version of strikeout', '', '', '', '', '', null, '5', 'rctd-034', '2018-01-15 16:21:42', 'https://www.javdoe.com/movie/rocket-av-rctd-034-route-yamazaki-five-big-tits-amateur-challenge-the-erotic-version-of-strikeout.html', '');
INSERT INTO `videos` VALUES ('121', '[RCTD-034] Unknown - Sexy Swimsuits Nip Slips! Strikeout!', '', '', '', '', '', null, '5', 'rctd-034', '2018-01-15 16:21:42', 'http://www.letsjav.com/en/watch/5B1blUx3v1WV3cDHEkITFKfAFx/viG0RpnTY4GzyUsOhbHEoYbKPs/rctd-034-sexy-swimsuits-nip-slips-strikeout/', '');
INSERT INTO `videos` VALUES ('122', 'RCTD-034 Polo With Harenchi Swimsuit!Stack Out', '', '', '', '', '', null, '5', 'rctd-034', '2018-01-15 16:21:42', 'http://javhub.net/play/f8176c0c9/5a52401b9e7e911d6e7865ca/RCTD-034-Polo-With-Harenchi-Swimsuit', '');
INSERT INTO `videos` VALUES ('123', '[CESD-502] Kagami Shizuka - Creampied Busty Mother-In-Law Who Became Her Son&#39;s Submissive Slave Shizuka Kagami', '', '', '', '', '', null, '6', 'CESD-502', '2018-01-15 16:21:42', 'http://www.letsjav.com/en/watch/P379M3a3npuGYUVT82aFZLLCqv/DBv67IzzFznKO7IYvOtLzzW5qO/cesd-502-creampied-busty-mother-in-law-who-became-her-sons-submissive-slave-shizuka-kagami/', '');
INSERT INTO `videos` VALUES ('124', 'CESD-502 My Son \'s Slave Became Slaughter Breast Breast Mother - In - Law Creative Vaginal Cum Shot Kagami Quietly', '', '', '', '', '', null, '6', 'CESD-502', '2018-01-15 16:21:42', 'http://javhub.net/play/6b4927fe5/5a55ca949e7e9154de83f898/CESD-502-My-Son-\'s-Slave-Became-Slaughter-Breast-Breast-Mother-In-Law-Creative-Vaginal-Cum-Shot-Kagami-Quietly', '');
INSERT INTO `videos` VALUES ('125', '[JUY-349] Tia - I Was Raped In Front Of My Late Husband&#39;s Picture, And I Came So Hard I Lost My Mind Tia', '', '', '', '', '', null, '7', 'JUY-349', '2018-01-15 16:21:42', 'http://www.letsjav.com/en/watch/aFJGhZMyYZ0Uhl99a20H1XBWsR/afXyAcj5gyZxFUBmi7sToD4gO1/juy-349-i-was-raped-in-front-of-my-late-husbands-picture-and-i-came-so-hard-i-lost-my-mind-tia/', '');
INSERT INTO `videos` VALUES ('126', 'JUY-349 I Was Caught In Front Of My Husband \'s Portrait, Caught Me Crazy. Tia', '', '', '', '', '', null, '7', 'JUY-349', '2018-01-15 16:21:42', 'http://javhub.net/play/d41eefcde/5a557ca79e7e9154de83f883/JUY-349-I-Was-Caught-In-Front-Of-My-Husband-\'s-Portrait,-Caught-Me-Crazy-Tia', '');
INSERT INTO `videos` VALUES ('127', 'BKD-24 Maternal And Child Copulation [path Yamanakako]', '', '', '', '', '', null, '8', 'bkd-24', '2018-01-15 16:21:42', 'http://javhub.net/play/a7994ee05/5a39da709e7e914c0506ad7a/BKD-24-Maternal-And-Child-Copulation-[path-Yamanakako]', '');
