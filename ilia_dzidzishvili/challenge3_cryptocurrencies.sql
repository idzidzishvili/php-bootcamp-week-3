CREATE TABLE `challenge3_cryptocurrencies` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `symbol` varchar(10) DEFAULT NULL,
  `price` decimal(20,10) DEFAULT '0.0000000000',
  `max_supply` bigint(20) DEFAULT NULL,
  `high` decimal(20,10) DEFAULT '0.0000000000',
  `logo_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;