<?php
/**
 * @PHP       Version >= 8.2
 * @copyright ©2024 Maatify.dev
 * @author    Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since     2024-07-17 11:24 AM
 * @link      https://www.maatify.dev Maatify.com
 * @link      https://github.com/Maatify/CronEmail  view project on GitHub
 * @Maatify   DB :: CronEmail
 */

namespace Maatify\CronEmailBlock;

use App\DB\DBS\DbConnector;

class CronEmailBlock extends DbConnector
{
    const TABLE_NAME                 = 'cron_email_block';
    const TABLE_ALIAS                = '';
    const IDENTIFY_TABLE_ID_COL_NAME = 'block_id';
    const LOGGER_TYPE                = self::TABLE_NAME;
    const LOGGER_SUB_TYPE            = '';
    const Cols                       =
        [
            self::IDENTIFY_TABLE_ID_COL_NAME => 1,
            'ct_id'                          => 1,
            'email'                          => 0,
            'description'                    => 0,
            'time'                           => 0,
            'admin_id'                       => 1,
        ];

    protected string $tableName = self::TABLE_NAME;
    protected string $tableAlias = self::TABLE_ALIAS;
    protected string $identify_table_id_col_name = self::IDENTIFY_TABLE_ID_COL_NAME;
    protected string $logger_type = self::LOGGER_TYPE;
    protected string $logger_sub_type = self::LOGGER_SUB_TYPE;
    protected array $cols = self::Cols;

    private static self $instance;

    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}