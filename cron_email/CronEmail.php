<?php
/**
 * @PHP       Version >= 8.0
 * @copyright ©2024 Maatify.dev
 * @author    Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since     2024-07-15 03:55 PM
 * @link      https://www.maatify.dev Maatify.com
 * @link      https://github.com/Maatify/CronEmail  view project on GitHub
 * @Maatify   DB :: CronEmail
 */

namespace Maatify\CronEmail;

use App\Assist\AppFunctions;
use App\DB\DBS\DbConnector;

abstract class CronEmail extends DbConnector
{
    const TABLE_NAME                 = 'cron_email';
    const TABLE_ALIAS                = '';
    const IDENTIFY_TABLE_ID_COL_NAME = 'cron_id';
    const LOGGER_TYPE                = self::TABLE_NAME;
    const LOGGER_SUB_TYPE            = '';
    const Cols           =
        [
            self::IDENTIFY_TABLE_ID_COL_NAME => 1,
            'type_id'                        => 1,
            'ct_id'                          => 1,
            'name'                           => 0,
            'email'                          => 0,
            'message'                        => 0,
            'subject'                        => 0,
            'record_time'                    => 0,
            'status'                        => 1,
            'sent_time'                      => 0,
        ];

    protected string $tableName = self::TABLE_NAME;
    protected string $tableAlias = self::TABLE_ALIAS;
    protected string $identify_table_id_col_name = self::IDENTIFY_TABLE_ID_COL_NAME;
    protected string $logger_type = self::LOGGER_TYPE;
    protected string $logger_sub_type = self::LOGGER_SUB_TYPE;
    protected array $cols = self::Cols;
    const TYPE_MESSAGE      = 1;
    const TYPE_CONFIRM_URL  = 2;
    const TYPE_CONFIRM_CODE = 3;
    const TYPE_TEMP_PASSWORD = 4;
    const TYPE_PROMOTION     = 5;
    const TYPE_ADMIN_MESSAGE = 7;

    const ALL_TYPES_NAME = [
        self::TYPE_MESSAGE => 'message',
        self::TYPE_CONFIRM_URL => 'confirm url',
        self::TYPE_CONFIRM_CODE => 'confirm code',
        self::TYPE_TEMP_PASSWORD => 'temp password',
        self::TYPE_PROMOTION => 'promotion',
        self::TYPE_ADMIN_MESSAGE => 'administrator message',
    ];

    protected function AddCron(int $ct_id, string $name, string $email, string $message, string $subject, int $type_id = 1): void
    {
        $this->Add([
            'ct_id'       => $ct_id,
            'type_id'     => $type_id,
            'name'        => $name,
            'email'       => $email,
            'message'     => $message,
            'subject'     => $subject,
            'record_time' => AppFunctions::CurrentDateTime(),
            'status'     => 0,
            'sent_time'   => AppFunctions::DefaultDateTime(),
        ]);
    }
}