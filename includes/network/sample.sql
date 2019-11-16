
CREATE DATABASE IF NOT EXISTS attendance_management;

USE attendance_management;

CREATE TABLE `users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(30) NOT NULL,
    `employee_num` INT(11) UNSIGNED NOT NULL,
    `mail` VARCHAR(50) NOT NULL,
    `pass` VARCHAR(100) NOT NULL,
    `is_admin` BIT NOT NULL DEFAULT 0,
    `created_time` TIMESTAMP,
    PRIMARY KEY(`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `hierarchical_relationships` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `boss_user_id` INT(11) UNSIGNED NOT NULL,
    `subordinate_user_id` INT(11) UNSIGNED NOT NULL,
    `created_time` TIMESTAMP,
    PRIMARY KEY(`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `attendances` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME,
    `breaktime` VARCHAR(30),
    `comment` VARCHAR(255),
    `business_type_id` INT(11) UNSIGNED,
    `remark_id` INT(11) UNSIGNED,
    `internal_business_id` INT(11) UNSIGNED,
    `user_id` INT(11) UNSIGNED,
    `created_time` TIMESTAMP,
    PRIMARY KEY(`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `business_types` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `created_time` TIMESTAMP,
    PRIMARY KEY(`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `internal_business_types` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `created_time` TIMESTAMP,
    PRIMARY KEY(`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `remarks` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `created_time` TIMESTAMP,
    PRIMARY KEY(`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `business_types` (name) VALUES
    ('社内業務'), ('社外業務');

INSERT INTO `internal_business_types` (name) VALUES
    ('内勤業務補佐'), ('カリキュラム進行'), ('MTG・開発等'), ('帰社日'), ('その他');

INSERT INTO `remarks` (name) VALUES
    ('有給'), ('遅刻'), ('早退'), ('欠勤'), ('特別休暇'), ('忌引'),('スポット稼働');