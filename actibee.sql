/*
 Navicat Premium Data Transfer

 Source Server         : actibee
 Source Server Type    : MySQL
 Source Server Version : 50651
 Source Host           : localhost:3306
 Source Schema         : actibee

 Target Server Type    : MySQL
 Target Server Version : 50651
 File Encoding         : 65001

 Date: 28/11/2021 19:08:20
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for activity
-- ----------------------------
DROP TABLE IF EXISTS `activity`;
CREATE TABLE `activity`  (
  `id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '主鍵',
  `member_id` int(11) NOT NULL COMMENT '會員主鍵，活動建立人',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '內容',
  `start_date` date NOT NULL COMMENT '活動開始時間',
  `end_date` date NOT NULL COMMENT '活動截止時間',
  `submit_max` int(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0為無人數限制，1以上有人數填寫限制',
  `is_cash` int(1) NOT NULL COMMENT '是否開啟金流服務,1為開啟,0為關閉',
  `style` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '美編樣式',
  `transfer_start_time` datetime(0) NULL DEFAULT NULL COMMENT '申請匯款時間',
  `transfer_end_time` datetime(0) NULL DEFAULT NULL COMMENT '完成匯款時間',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `member_id`(`member_id`) USING BTREE,
  CONSTRAINT `activity_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of activity
-- ----------------------------

-- ----------------------------
-- Table structure for activity_cover
-- ----------------------------
DROP TABLE IF EXISTS `activity_cover`;
CREATE TABLE `activity_cover`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `activity_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '活動主鍵',
  `data` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '可動式欄位，用於儲存圖片網址',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  CONSTRAINT `activity_cover_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 34 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of activity_cover
-- ----------------------------

-- ----------------------------
-- Table structure for activity_fee
-- ----------------------------
DROP TABLE IF EXISTS `activity_fee`;
CREATE TABLE `activity_fee`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `activity_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '活動主鍵',
  `add_time` datetime(0) NOT NULL COMMENT '新增時間',
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'free=自由定價、fix=單個價格、multi=多個價格',
  `data` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '可動式欄位，用於儲存資訊付款資訊',
  `is_history` int(1) NOT NULL COMMENT ' 是否為歷史紀錄，',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  CONSTRAINT `activity_fee_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 147 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of activity_fee
-- ----------------------------

-- ----------------------------
-- Table structure for activity_form
-- ----------------------------
DROP TABLE IF EXISTS `activity_form`;
CREATE TABLE `activity_form`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `activity_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '活動主鍵',
  `add_time` datetime(0) NOT NULL COMMENT '加入時間',
  `data` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '可動式欄位，用於紀錄每個input的內容',
  `is_history` int(1) NOT NULL COMMENT ' 是否為歷史紀錄，',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  CONSTRAINT `activity_form_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 80 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of activity_form
-- ----------------------------

-- ----------------------------
-- Table structure for member
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `school_id` int(11) NULL DEFAULT NULL COMMENT '學校主鍵',
  `account` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '帳號',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '密碼',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '暱稱',
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `member_ibfk_1`(`school_id`) USING BTREE,
  CONSTRAINT `member_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of member
-- ----------------------------

-- ----------------------------
-- Table structure for member_sensitive
-- ----------------------------
DROP TABLE IF EXISTS `member_sensitive`;
CREATE TABLE `member_sensitive`  (
  `member_id` int(11) NOT NULL COMMENT '會員主鍵',
  `real_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '真實姓名',
  `student_id` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '學號',
  `id_card_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '學生證圖像檔案名稱',
  `file` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '相關證明文件',
  `cellphone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '手機號碼',
  `group` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '單位(社團或學會)',
  `postion` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '職稱',
  `update_time` datetime(0) NULL DEFAULT NULL COMMENT '最後上傳時間',
  `verify_time` datetime(0) NULL DEFAULT NULL COMMENT '最後驗證時間',
  `verify` int(1) NOT NULL COMMENT '0=未審核,1=審核通過,2=審核失敗可修改',
  `reason` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '原因',
  PRIMARY KEY (`member_id`) USING BTREE,
  CONSTRAINT `member_sensitive_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of member_sensitive
-- ----------------------------

-- ----------------------------
-- Table structure for member_verify
-- ----------------------------
DROP TABLE IF EXISTS `member_verify`;
CREATE TABLE `member_verify`  (
  `member_id` int(255) NOT NULL COMMENT '會員主鍵',
  `token` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '亂數判斷碼',
  `time` datetime(0) NULL DEFAULT NULL COMMENT '認證時間',
  PRIMARY KEY (`member_id`) USING BTREE,
  CONSTRAINT `member_verify_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of member_verify
-- ----------------------------

-- ----------------------------
-- Table structure for member_wallet
-- ----------------------------
DROP TABLE IF EXISTS `member_wallet`;
CREATE TABLE `member_wallet`  (
  `member_id` int(11) NOT NULL,
  `activity_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '活動主鍵',
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '銀行代號',
  `bank` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '銀行名稱',
  `branch` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '分行名稱',
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '收款帳號',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '帳戶名稱（本人）',
  PRIMARY KEY (`member_id`, `activity_id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  CONSTRAINT `member_wallet_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `member_wallet_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of member_wallet
-- ----------------------------

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order`  (
  `id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '訂單號',
  `activity_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '活動主鍵',
  `activity_fee_id` int(11) NULL DEFAULT NULL COMMENT '參考之付款方案',
  `trade_no` int(11) NULL DEFAULT NULL COMMENT '綠界交易序號(由我方生成)',
  `trade_amt` int(11) NULL DEFAULT NULL COMMENT '綠界交易金額',
  `handling` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '手續費',
  `payment_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '付款方式',
  `expire_date` datetime(0) NULL DEFAULT NULL COMMENT '訂單付款期限(信用卡無)',
  `trade_date` datetime(0) NOT NULL COMMENT '訂單成立時間',
  `payment_date` datetime(0) NULL DEFAULT NULL COMMENT '訂單付款時間',
  `rtn_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '交易待碼 1 為成功',
  `rtn_msg` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '交易訊息',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_fee_id`(`activity_fee_id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  CONSTRAINT `order_ibfk_1` FOREIGN KEY (`activity_fee_id`) REFERENCES `activity_fee` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `order_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of order
-- ----------------------------

-- ----------------------------
-- Table structure for order_activity_form
-- ----------------------------
DROP TABLE IF EXISTS `order_activity_form`;
CREATE TABLE `order_activity_form`  (
  `order_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '訂單主鍵',
  `activity_form_id` int(11) NOT NULL COMMENT '所參考表單格式',
  `data` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '可動式欄位，用於紀錄每個input的內容',
  PRIMARY KEY (`order_id`, `activity_form_id`) USING BTREE,
  INDEX `activity_form_id`(`activity_form_id`) USING BTREE,
  CONSTRAINT `order_activity_form_ibfk_2` FOREIGN KEY (`activity_form_id`) REFERENCES `activity_form` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `order_activity_form_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of order_activity_form
-- ----------------------------

-- ----------------------------
-- Table structure for order_member
-- ----------------------------
DROP TABLE IF EXISTS `order_member`;
CREATE TABLE `order_member`  (
  `order_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '訂單主鍵',
  `member_id` int(11) NOT NULL COMMENT '會員主鍵，付款會員',
  PRIMARY KEY (`order_id`, `member_id`) USING BTREE,
  INDEX `member_id`(`member_id`) USING BTREE,
  CONSTRAINT `order_member_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `order_member_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of order_member
-- ----------------------------

-- ----------------------------
-- Table structure for school
-- ----------------------------
DROP TABLE IF EXISTS `school`;
CREATE TABLE `school`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `mail` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of school
-- ----------------------------
INSERT INTO `school` VALUES (3, '高雄師範大學', 'mail.nknu.edu.tw');
INSERT INTO `school` VALUES (4, '測試大學以Google信箱', 'gmail.com');

SET FOREIGN_KEY_CHECKS = 1;
