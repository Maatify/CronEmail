[![Current version](https://img.shields.io/packagist/v/maatify/cron-email)][pkg]
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/maatify/cron-email)][pkg]
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/cron-email)][pkg-stats]
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/cron-email)][pkg-stats]
[![Stars](https://img.shields.io/packagist/stars/maatify/cron-email)](https://github.com/maatify/CronEmail/stargazers)

[pkg]: <https://packagist.org/packages/maatify/cron-email>
[pkg-stats]: <https://packagist.org/packages/maatify/routee/cron-email>
# Installation

```shell
composer require maatify/cron-email
```

## Database Structure
```mysql

--
-- Database: `maatify`
--

-- --------------------------------------------------------

--
-- Table structure for table `cron_email`
--

CREATE TABLE `cron_email` (
      `cron_id` int(11) NOT NULL,
      `type_id` int(11) NOT NULL DEFAULT '1' COMMENT '1=messge; 2=confirm; 3=promotion',
      `ct_id` int(11) NOT NULL DEFAULT '0',
      `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
      `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
      `message` mediumtext COLLATE utf8mb4_unicode_ci,
      `subject` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
      `record_time` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
      `status` tinyint(1) NOT NULL DEFAULT '0',
      `sent_time` datetime NOT NULL DEFAULT '1900-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------

--
-- Table structure for table `cron_email_block`
--

CREATE TABLE `cron_email_block` (
  `block_id` int NOT NULL,
  `ct_id` int NOT NULL DEFAULT '0',
  `email` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `time` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  `admin_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_email_off`
--

CREATE TABLE `cron_email_off` (
  `off_id` int NOT NULL,
  `ct_id` int NOT NULL DEFAULT '0',
  `email` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `time` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  `admin_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cron_email`
--
ALTER TABLE `cron_email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_email_block`
--
ALTER TABLE `cron_email_block`
  ADD PRIMARY KEY (`block_id`);

--
-- Indexes for table `cron_email_off`
--
ALTER TABLE `cron_email_off`
  ADD PRIMARY KEY (`off_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cron_email`
--
ALTER TABLE `cron_email`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_email_block`
--
ALTER TABLE `cron_email_block`
  MODIFY `block_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_email_off`
--
ALTER TABLE `cron_email_off`
  MODIFY `off_id` int NOT NULL AUTO_INCREMENT;
COMMIT;
```