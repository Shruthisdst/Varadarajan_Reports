<?php

define('JOURNAL_DB_SCHEMA', 'CREATE DATABASE IF NOT EXISTS :db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci');

define('METADATA_TABLE_SCHEMA', 'CREATE TABLE `' . METADATA_TABLE . '` (
  `bcode` varchar(20) DEFAULT NULL,
  `btitle` varchar(500) DEFAULT NULL,
  `level` int(4) DEFAULT NULL,
  `title` varchar(10000) DEFAULT NULL,
  `page` varchar(20) DEFAULT NULL,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT = 1000 ENGINE=MyISAM DEFAULT CHARSET=utf8mb4');

define('FULLTEXT_TABLE_SCHEMA', 'CREATE TABLE `' . FULLTEXT_TABLE . '` (
  `text` text,
  `bcode` varchar(50) NOT NULL,
   `btitle` varchar(500) DEFAULT NULL,
  `page` varchar(20) DEFAULT NULL,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4');

define('FULLTEXT_INDEX_SCHEMA', 'CREATE FULLTEXT INDEX text_index ON ' . FULLTEXT_TABLE . ' (text);');

define('CHAR_ENCODING_SCHEMA', 'SET NAMES utf8mb4');


?>
