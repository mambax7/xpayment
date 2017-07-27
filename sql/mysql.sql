CREATE TABLE `xpayment_discounts` (
  `did`       INT(30) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid`       INT(13) UNSIGNED          DEFAULT '0',
  `code`      VARCHAR(48)               DEFAULT NULL,
  `email`     VARCHAR(255)              DEFAULT NULL,
  `validtill` INT(13) UNSIGNED          DEFAULT '0',
  `redeems`   INT(10) UNSIGNED          DEFAULT '0',
  `discount`  DECIMAL(10, 4)            DEFAULT '0.0000',
  `redeemed`  INT(13) UNSIGNED          DEFAULT '0',
  `iids`      VARCHAR(1000)             DEFAULT NULL,
  `sent`      INT(13) UNSIGNED          DEFAULT '0',
  `created`   INT(13) UNSIGNED          DEFAULT '0',
  `updated`   INT(13) UNSIGNED          DEFAULT '0',
  PRIMARY KEY (`did`)
)
  ENGINE = INNODB
  DEFAULT CHARSET = utf8;

CREATE TABLE `xpayment_gateway` (
  `gid`         INT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `testmode`    TINYINT(2)               DEFAULT '0',
  `name`        VARCHAR(255)             DEFAULT NULL,
  `class`       VARCHAR(255)             DEFAULT NULL,
  `description` VARCHAR(255)             DEFAULT NULL,
  `author`      VARCHAR(255)             DEFAULT NULL,
  `salt`        VARCHAR(255)             DEFAULT NULL,
  PRIMARY KEY (`gid`),
  KEY `SEARCH` (`gid`, `name`(12), `class`(13))
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `xpayment_gateway_options` (
  `goid`     INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gid`      INT(6) UNSIGNED  NOT NULL,
  `name`     VARCHAR(255)              DEFAULT NULL,
  `value`    VARCHAR(1000)             DEFAULT NULL,
  `refereer` VARCHAR(255)              DEFAULT NULL,
  PRIMARY KEY (`goid`),
  KEY `SEARCH` (`gid`, `name`, `refereer`(12))
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `xpayment_groups` (
  `rid`     INT(12) UNSIGNED NOT NULL                AUTO_INCREMENT,
  `mode`    ENUM ('BROKERS', 'ACCOUNTS', 'OFFICERS') DEFAULT NULL,
  `plugin`  VARCHAR(128)                             DEFAULT '*',
  `uid`     INT(13)                                  DEFAULT '0',
  `limit`   TINYINT(2)                               DEFAULT '0',
  `minimum` DECIMAL(15, 2)                           DEFAULT '0.00',
  `maximum` DECIMAL(15, 2)                           DEFAULT '0.00',
  PRIMARY KEY (`rid`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `xpayment_invoice` (
  `iid`                      INT(15) UNSIGNED                                                                NOT NULL AUTO_INCREMENT,
  `mode`                     ENUM ('PAID', 'UNPAID', 'CANCEL')                                               NOT NULL DEFAULT 'UNPAID',
  `plugin`                   VARCHAR(128)                                                                    NOT NULL,
  `return`                   VARCHAR(1000)                                                                   NOT NULL,
  `cancel`                   VARCHAR(1000)                                                                   NOT NULL,
  `ipn`                      VARCHAR(1000)                                                                   NOT NULL,
  `invoicenumber`            VARCHAR(64)                                                                              DEFAULT NULL,
  `drawfor`                  VARCHAR(255)                                                                             DEFAULT NULL,
  `drawto`                   VARCHAR(255)                                                                    NOT NULL,
  `drawto_email`             VARCHAR(255)                                                                    NOT NULL,
  `paid`                     DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `amount`                   DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `grand`                    DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `shipping`                 DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `handling`                 DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `weight`                   DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `weight_unit`              ENUM ('lbs', 'kgs')                                                                      DEFAULT 'kgs',
  `tax`                      DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `interest`                 DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `rate`                     DECIMAL(10, 4)                                                                           DEFAULT '0.0000',
  `discount`                 DECIMAL(10, 4)                                                                           DEFAULT '0.0000',
  `discount_amount`          DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `did`                      INT(30) UNSIGNED                                                                         DEFAULT '0',
  `currency`                 VARCHAR(3)                                                                               DEFAULT 'AUD',
  `items`                    DECIMAL(15, 3)                                                                           DEFAULT '0.000',
  `key`                      VARCHAR(255)                                                                             DEFAULT NULL,
  `transactionid`            VARCHAR(255)                                                                             DEFAULT NULL,
  `gateway`                  VARCHAR(128)                                                                             DEFAULT NULL,
  `topayment`                INT(13)                                                                                  DEFAULT '0',
  `created`                  INT(13)                                                                                  DEFAULT '0',
  `updated`                  INT(13)                                                                                  DEFAULT '0',
  `actioned`                 INT(13)                                                                                  DEFAULT '0',
  `reoccurrence`             INT(8)                                                                                   DEFAULT '0',
  `reoccurrence_period_days` INT(8)                                                                                   DEFAULT '0',
  `reoccurrences`            INT(8)                                                                                   DEFAULT '0',
  `occurrence`               INT(13)                                                                                  DEFAULT '0',
  `previous`                 INT(13)                                                                                  DEFAULT '0',
  `occurrence_amount`        DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `occurrence_grand`         DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `occurrence_shipping`      DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `occurrence_handling`      DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `occurrence_tax`           DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `occurrence_weight`        DECIMAL(15, 6)                                                                           DEFAULT '0.000000',
  `remittion`                ENUM ('NONE', 'PENDING', 'NOTICE', 'COLLECT', 'FRAUD', 'SETTLED', 'DISCOUNTED') NOT NULL DEFAULT 'NONE',
  `remittion_settled`        DECIMAL(15, 2)                                                                           DEFAULT '0.00',
  `donation`                 TINYINT(2)                                                                               DEFAULT '0',
  `comment`                  VARCHAR(5000)                                                                            DEFAULT NULL,
  `user_ip`                  VARCHAR(128)                                                                             DEFAULT NULL,
  `user_netaddy`             VARCHAR(255)                                                                             DEFAULT NULL,
  `user_uid`                 INT(13)                                                                                  DEFAULT '0',
  `user_uids`                VARCHAR(1000)                                                                            DEFAULT NULL,
  `broker_uids`              VARCHAR(1000)                                                                            DEFAULT NULL,
  `accounts_uids`            VARCHAR(1000)                                                                            DEFAULT NULL,
  `officer_uids`             VARCHAR(1000)                                                                            DEFAULT NULL,
  `remitted`                 INT(13)                                                                                  DEFAULT '0',
  `user_ipdb_country_code`   VARCHAR(3)                                                                               DEFAULT NULL,
  `user_ipdb_country_name`   VARCHAR(128)                                                                             DEFAULT NULL,
  `user_ipdb_region_name`    VARCHAR(128)                                                                             DEFAULT NULL,
  `user_ipdb_city_name`      VARCHAR(128)                                                                             DEFAULT NULL,
  `user_ipdb_postcode`       VARCHAR(15)                                                                              DEFAULT NULL,
  `user_ipdb_latitude`       DECIMAL(5, 5)                                                                            DEFAULT '0.00000',
  `user_ipdb_longitude`      DECIMAL(5, 5)                                                                            DEFAULT '0.00000',
  `user_ipdb_time_zone`      VARCHAR(6)                                                                               DEFAULT '00:00',
  `fraud_ipdb_errors`        VARCHAR(1000)                                                                            DEFAULT NULL,
  `fraud_ipdb_warnings`      VARCHAR(1000)                                                                            DEFAULT NULL,
  `fraud_ipdb_messages`      VARCHAR(1000)                                                                            DEFAULT NULL,
  `fraud_ipdb_districtcity`  VARCHAR(128)                                                                             DEFAULT NULL,
  `fraud_ipdb_ipcountrycode` VARCHAR(3)                                                                               DEFAULT NULL,
  `fraud_ipdb_ipcountry`     VARCHAR(128)                                                                             DEFAULT NULL,
  `fraud_ipdb_ipregioncode`  VARCHAR(3)                                                                               DEFAULT NULL,
  `fraud_ipdb_ipregion`      VARCHAR(128)                                                                             DEFAULT NULL,
  `fraud_ipdb_ipcity`        VARCHAR(128)                                                                             DEFAULT NULL,
  `fraud_ipdb_score`         INT(10)                                                                                  DEFAULT NULL,
  `fraud_ipdb_accuracyscore` INT(10)                                                                                  DEFAULT NULL,
  `fraud_ipdb_scoredetails`  MEDIUMTEXT,
  `due`                      INT(13)                                                                                  DEFAULT '0',
  `collect`                  INT(13)                                                                                  DEFAULT '0',
  `wait`                     INT(13)                                                                                  DEFAULT '0',
  `offline`                  INT(13)                                                                                  DEFAULT '0',
  `annum`                    INT(13)                                                                                  DEFAULT '0',
  PRIMARY KEY (`iid`),
  KEY `SEARCH` (`iid`, `mode`, `currency`, `items`, `remittion`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `xpayment_invoice_items` (
  `iiid`        INT(26) UNSIGNED                                                    NOT NULL AUTO_INCREMENT,
  `iid`         INT(15) UNSIGNED                                                    NOT NULL,
  `cat`         VARCHAR(255)                                                                 DEFAULT NULL,
  `name`        VARCHAR(255)                                                                 DEFAULT NULL,
  `amount`      DECIMAL(19, 4)                                                               DEFAULT '0.0000',
  `quantity`    DECIMAL(15, 3)                                                               DEFAULT '0.000',
  `shipping`    DECIMAL(15, 2)                                                               DEFAULT '0.00',
  `handling`    DECIMAL(15, 2)                                                               DEFAULT '0.00',
  `weight`      DECIMAL(15, 6)                                                               DEFAULT '0.000000',
  `tax`         DECIMAL(15, 2)                                                               DEFAULT '0.00',
  `description` VARCHAR(5000)                                                                DEFAULT NULL,
  `mode`        ENUM ('PURCHASED', 'REFUNDED', 'UNDELIVERED', 'DAMAGED', 'EXPRESS') NOT NULL DEFAULT 'PURCHASED',
  `created`     INT(13)                                                                      DEFAULT '0',
  `updated`     INT(13)                                                                      DEFAULT '0',
  `actioned`    INT(13)                                                                      DEFAULT '0',
  PRIMARY KEY (`iiid`),
  KEY `SEARCH` (`iid`, `cat`(12), `name`(12))
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `xpayment_invoice_transactions` (
  `tiid`           INT(28) UNSIGNED                                         NOT NULL AUTO_INCREMENT,
  `iid`            INT(23) UNSIGNED                                         NOT NULL,
  `transactionid`  VARCHAR(255)                                                      DEFAULT NULL,
  `email`          VARCHAR(255)                                                      DEFAULT NULL,
  `invoice`        VARCHAR(255)                                                      DEFAULT NULL,
  `custom`         VARCHAR(255)                                                      DEFAULT NULL,
  `status`         VARCHAR(255)                                                      DEFAULT NULL,
  `date`           INT(13)                                                           DEFAULT '0',
  `gross`          DECIMAL(15, 2)                                                    DEFAULT '0.00',
  `fee`            DECIMAL(15, 2)                                                    DEFAULT '0.00',
  `deposit`        DECIMAL(15, 2)                                                    DEFAULT '0.00',
  `settle`         DECIMAL(15, 2)                                                    DEFAULT '0.00',
  `exchangerate`   VARCHAR(128)                                                      DEFAULT NULL,
  `firstname`      VARCHAR(255)                                                      DEFAULT NULL,
  `lastname`       VARCHAR(255)                                                      DEFAULT NULL,
  `street`         VARCHAR(255)                                                      DEFAULT NULL,
  `city`           VARCHAR(255)                                                      DEFAULT NULL,
  `state`          VARCHAR(255)                                                      DEFAULT NULL,
  `postcode`       VARCHAR(255)                                                      DEFAULT NULL,
  `country`        VARCHAR(255)                                                      DEFAULT NULL,
  `address_status` VARCHAR(255)                                                      DEFAULT NULL,
  `payer_email`    VARCHAR(255)                                                      DEFAULT NULL,
  `payer_status`   VARCHAR(255)                                                      DEFAULT NULL,
  `gateway`        VARCHAR(128)                                                      DEFAULT NULL,
  `plugin`         VARCHAR(128)                                                      DEFAULT NULL,
  `mode`           ENUM ('PAYMENT', 'REFUND', 'PENDING', 'NOTICE', 'OTHER') NOT NULL DEFAULT 'PAYMENT',
  PRIMARY KEY (`tiid`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `xpayment_autotax` (
  `id`      INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `country` VARCHAR(128)     NOT NULL,
  `code`    VARCHAR(3)       NOT NULL,
  `rate`    DECIMAL(10, 5)            DEFAULT '0.00000',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 256
  DEFAULT CHARSET = utf8;
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (1, 'AFGHANISTAN', 'AF', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (2, 'ALAND ISLANDS', 'AX', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (3, 'ALBANIA', 'AL', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (4, 'ALGERIA', 'DZ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (5, 'AMERICAN SAMOA', 'AS', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (6, 'ANDORRA', 'AD', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (7, 'ANGOLA', 'AO', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (8, 'ANGUILLA', 'AI', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (9, 'ANTARCTICA', 'AQ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (10, 'ANTIGUA AND BARBUDA', 'AG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (11, 'ARGENTINA', 'AR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (12, 'ARMENIA', 'AM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (13, 'ARUBA', 'AW', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (14, 'AUSTRALIA', 'AU', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (15, 'AUSTRIA', 'AT', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (16, 'AZERBAIJAN', 'AZ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (17, 'BAHAMAS', 'BS', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (18, 'BAHRAIN', 'BH', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (19, 'BANGLADESH', 'BD', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (20, 'BARBADOS', 'BB', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (21, 'BELARUS', 'BY', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (22, 'BELGIUM', 'BE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (23, 'BELIZE', 'BZ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (24, 'BENIN', 'BJ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (25, 'BERMUDA', 'BM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (26, 'BHUTAN', 'BT', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (27, 'BOLIVIA, PLURINATIONAL STATE OF', 'BO', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (28, 'BONAIRE, SAINT EUSTATIUS AND SABA', 'BQ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (29, 'BOSNIA AND HERZEGOVINA', 'BA', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (30, 'BOTSWANA', 'BW', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (31, 'BOUVET ISLAND', 'BV', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (32, 'BRAZIL', 'BR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (33, 'BRITISH INDIAN OCEAN TERRITORY', 'IO', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (34, 'BRUNEI DARUSSALAM', 'BN', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (35, 'BULGARIA', 'BG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (36, 'BURKINA FASO', 'BF', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (37, 'BURUNDI', 'BI', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (38, 'CAMBODIA', 'KH', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (39, 'CAMEROON', 'CM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (40, 'CANADA', 'CA', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (41, 'CAPE VERDE', 'CV', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (42, 'CAYMAN ISLANDS', 'KY', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (43, 'CENTRAL AFRICAN REPUBLIC', 'CF', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (44, 'CHAD', 'TD', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (45, 'CHILE', 'CL', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (46, 'CHINA', 'CN', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (47, 'CHRISTMAS ISLAND', 'CX', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (48, 'COCOS (KEELING) ISLANDS', 'CC', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (49, 'COLOMBIA', 'CO', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (50, 'COMOROS', 'KM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (51, 'CONGO', 'CG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (52, 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'CD', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (53, 'COOK ISLANDS', 'CK', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (54, 'COSTA RICA', 'CR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (55, 'COTE D\'IVOIRE', 'CI', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (56, 'CROATIA', 'HR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (57, 'CUBA', 'CU', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (58, 'CURACAO', 'CW', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (59, 'CYPRUS', 'CY', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (60, 'CZECH REPUBLIC', 'CZ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (61, 'DENMARK', 'DK', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (62, 'DJIBOUTI', 'DJ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (63, 'DOMINICA', 'DM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (64, 'DOMINICAN REPUBLIC', 'DO', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (65, 'ECUADOR', 'EC', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (66, 'EGYPT', 'EG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (67, 'EL SALVADOR', 'SV', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (68, 'EQUATORIAL GUINEA', 'GQ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (69, 'ERITREA', 'ER', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (70, 'ESTONIA', 'EE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (71, 'ETHIOPIA', 'ET', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (72, 'FALKLAND ISLANDS (MALVINAS)', 'FK', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (73, 'FAROE ISLANDS', 'FO', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (74, 'FIJI', 'FJ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (75, 'FINLAND', 'FI', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (76, 'FRANCE', 'FR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (77, 'FRENCH GUIANA', 'GF', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (78, 'FRENCH POLYNESIA', 'PF', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (79, 'FRENCH SOUTHERN TERRITORIES', 'TF', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (80, 'GABON', 'GA', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (81, 'GAMBIA', 'GM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (82, 'GEORGIA', 'GE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (83, 'GERMANY', 'DE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (84, 'GHANA', 'GH', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (85, 'GIBRALTAR', 'GI', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (86, 'GREECE', 'GR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (87, 'GREENLAND', 'GL', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (88, 'GRENADA', 'GD', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (89, 'GUADELOUPE', 'GP', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (90, 'GUAM', 'GU', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (91, 'GUATEMALA', 'GT', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (92, 'GUERNSEY', 'GG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (93, 'GUINEA', 'GN', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (94, 'GUINEA-BISSAU', 'GW', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (95, 'GUYANA', 'GY', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (96, 'HAITI', 'HT', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (97, 'HEARD ISLAND AND MCDONALD ISLANDS', 'HM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (98, 'HOLY SEE (VATICAN CITY STATE)', 'VA', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (99, 'HONDURAS', 'HN', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (100, 'HONG KONG', 'HK', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (101, 'HUNGARY', 'HU', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (102, 'ICELAND', 'IS', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (103, 'INDIA', 'IN', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (104, 'INDONESIA', 'ID', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (105, 'IRAN, ISLAMIC REPUBLIC OF', 'IR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (106, 'IRAQ', 'IQ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (107, 'IRELAND', 'IE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (108, 'ISLE OF MAN', 'IM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (109, 'ISRAEL', 'IL', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (110, 'ITALY', 'IT', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (111, 'JAMAICA', 'JM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (112, 'JAPAN', 'JP', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (113, 'JERSEY', 'JE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (114, 'JORDAN', 'JO', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (115, 'KAZAKHSTAN', 'KZ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (116, 'KENYA', 'KE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (117, 'KIRIBATI', 'KI', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (118, 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'KP', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (119, 'KOREA, REPUBLIC OF', 'KR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (120, 'KUWAIT', 'KW', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (121, 'KYRGYZSTAN', 'KG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (122, 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'LA', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (123, 'LATVIA', 'LV', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (124, 'LEBANON', 'LB', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (125, 'LESOTHO', 'LS', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (126, 'LIBERIA', 'LR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (127, 'LIBYAN ARAB JAMAHIRIYA', 'LY', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (128, 'LIECHTENSTEIN', 'LI', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (129, 'LITHUANIA', 'LT', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (130, 'LUXEMBOURG', 'LU', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (131, 'MACAO', 'MO', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (132, 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'MK', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (133, 'MADAGASCAR', 'MG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (134, 'MALAWI', 'MW', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (135, 'MALAYSIA', 'MY', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (136, 'MALDIVES', 'MV', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (137, 'MALI', 'ML', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (138, 'MALTA', 'MT', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (139, 'MARSHALL ISLANDS', 'MH', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (140, 'MARTINIQUE', 'MQ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (141, 'MAURITANIA', 'MR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (142, 'MAURITIUS', 'MU', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (143, 'MAYOTTE', 'YT', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (144, 'MEXICO', 'MX', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (145, 'MICRONESIA, FEDERATED STATES OF', 'FM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (146, 'MOLDOVA, REPUBLIC OF', 'MD', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (147, 'MONACO', 'MC', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (148, 'MONGOLIA', 'MN', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (149, 'MONTENEGRO', 'ME', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (150, 'MONTSERRAT', 'MS', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (151, 'MOROCCO', 'MA', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (152, 'MOZAMBIQUE', 'MZ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (153, 'MYANMAR', 'MM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (154, 'NAMIBIA', 'NA', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (155, 'NAURU', 'NR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (156, 'NEPAL', 'NP', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (157, 'NETHERLANDS', 'NL', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (158, 'NEW CALEDONIA', 'NC', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (159, 'NEW ZEALAND', 'NZ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (160, 'NICARAGUA', 'NI', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (161, 'NIGER', 'NE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (162, 'NIGERIA', 'NG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (163, 'NIUE', 'NU', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (164, 'NORFOLK ISLAND', 'NF', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (165, 'NORTHERN MARIANA ISLANDS', 'MP', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (166, 'NORWAY', 'NO', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (167, 'OMAN', 'OM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (168, 'PAKISTAN', 'PK', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (169, 'PALAU', 'PW', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (170, 'PALESTINIAN TERRITORY, OCCUPIED', 'PS', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (171, 'PANAMA', 'PA', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (172, 'PAPUA NEW GUINEA', 'PG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (173, 'PARAGUAY', 'PY', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (174, 'PERU', 'PE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (175, 'PHILIPPINES', 'PH', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (176, 'PITCAIRN', 'PN', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (177, 'POLAND', 'PL', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (178, 'PORTUGAL', 'PT', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (179, 'PUERTO RICO', 'PR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (180, 'QATAR', 'QA', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (181, 'REUNION', 'RE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (182, 'ROMANIA', 'RO', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (183, 'RUSSIAN FEDERATION', 'RU', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (184, 'RWANDA', 'RW', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (185, 'SAINT BARTHELEMY', 'BL', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (186, 'SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA', 'SH', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (187, 'SAINT KITTS AND NEVIS', 'KN', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (188, 'SAINT LUCIA', 'LC', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (189, 'SAINT MARTIN (FRENCH PART)', 'MF', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (190, 'SAINT PIERRE AND MIQUELON', 'PM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (191, 'SAINT VINCENT AND THE GRENADINES', 'VC', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (192, 'SAMOA', 'WS', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (193, 'SAN MARINO', 'SM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (194, 'SAO TOME AND PRINCIPE', 'ST', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (195, 'SAUDI ARABIA', 'SA', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (196, 'SENEGAL', 'SN', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (197, 'SERBIA', 'RS', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (198, 'SEYCHELLES', 'SC', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (199, 'SIERRA LEONE', 'SL', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (200, 'SINGAPORE', 'SG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (201, 'SINT MAARTEN (DUTCH PART)', 'SX', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (202, 'SLOVAKIA', 'SK', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (203, 'SLOVENIA', 'SI', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (204, 'SOLOMON ISLANDS', 'SB', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (205, 'SOMALIA', 'SO', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (206, 'SOUTH AFRICA', 'ZA', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (207, 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'GS', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (208, 'SPAIN', 'ES', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (209, 'SRI LANKA', 'LK', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (210, 'SUDAN', 'SD', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (211, 'SURINAME', 'SR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (212, 'SVALBARD AND JAN MAYEN', 'SJ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (213, 'SWAZILAND', 'SZ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (214, 'SWEDEN', 'SE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (215, 'SWITZERLAND', 'CH', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (216, 'SYRIAN ARAB REPUBLIC', 'SY', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (217, 'TAIWAN, PROVINCE OF CHINA', 'TW', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (218, 'TAJIKISTAN', 'TJ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (219, 'TANZANIA, UNITED REPUBLIC OF', 'TZ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (220, 'THAILAND', 'TH', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (221, 'TIMOR-LESTE', 'TL', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (222, 'TOGO', 'TG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (223, 'TOKELAU', 'TK', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (224, 'TONGA', 'TO', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (225, 'TRINIDAD AND TOBAGO', 'TT', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (226, 'TUNISIA', 'TN', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (227, 'TURKEY', 'TR', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (228, 'TURKMENISTAN', 'TM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (229, 'TURKS AND CAICOS ISLANDS', 'TC', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (230, 'TUVALU', 'TV', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (231, 'UGANDA', 'UG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (232, 'UKRAINE', 'UA', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (233, 'UNITED ARAB EMIRATES', 'AE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (234, 'UNITED KINGDOM', 'GB', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (235, 'UNITED STATES', 'US', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (236, 'UNITED STATES MINOR OUTLYING ISLANDS', 'UM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (237, 'URUGUAY', 'UY', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (238, 'UZBEKISTAN', 'UZ', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (239, 'VANUATU', 'VU', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (240, 'VENEZUELA, BOLIVARIAN REPUBLIC OF', 'VE', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (241, 'VIET NAM', 'VN', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (242, 'VIRGIN ISLANDS, BRITISH', 'VG', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (243, 'VIRGIN ISLANDS, U.S.', 'VI', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (244, 'WALLIS AND FUTUNA', 'WF', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (245, 'WESTERN SAHARA', 'EH', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (246, 'YEMEN', 'YE0', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (247, 'ZAMBIA', 'ZM', '0.00000');
INSERT INTO `xpayment_autotax` (`id`, `country`, `code`, `rate`) VALUES (248, 'ZIMBABWE', 'ZW', '0.00000');
