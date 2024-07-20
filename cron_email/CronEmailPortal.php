<?php
/**
 * @PHP       Version >= 8.0
 * @copyright ©2024 Maatify.dev
 * @author    Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since     2024-07-17 11:28 AM
 * @link      https://www.maatify.dev Maatify.com
 * @link      https://github.com/Maatify/CronEmail  view project on GitHub
 * @Maatify   DB :: CronEmail
 */

namespace Maatify\CronEmail;

use Maatify\Json\Json;
use Maatify\PostValidatorV2\ValidatorConstantsTypes;
use Maatify\PostValidatorV2\ValidatorConstantsValidators;

class CronEmailPortal extends CronEmailDbPortalHandler
{
    const TABLE_NAME                 = CronEmail::TABLE_NAME;
    const TABLE_ALIAS                = CronEmail::TABLE_ALIAS;
    const IDENTIFY_TABLE_ID_COL_NAME = CronEmail::IDENTIFY_TABLE_ID_COL_NAME;
    const LOGGER_TYPE                = CronEmail::LOGGER_TYPE;
    const LOGGER_SUB_TYPE            = CronEmail::LOGGER_SUB_TYPE;
    const Cols                       = CronEmail::Cols;

    protected string $tableName = self::TABLE_NAME;
    protected string $tableAlias = self::TABLE_ALIAS;
    protected string $identify_table_id_col_name = self::IDENTIFY_TABLE_ID_COL_NAME;
    protected array $cols = self::Cols;
    private static self $instance;

    protected array $cols_to_filter = [
        [self::IDENTIFY_TABLE_ID_COL_NAME, ValidatorConstantsTypes::Int, ValidatorConstantsValidators::Optional],
        ['ct_id', ValidatorConstantsTypes::Int, ValidatorConstantsValidators::Optional],
        [ValidatorConstantsTypes::Status, ValidatorConstantsTypes::Status, ValidatorConstantsValidators::Optional],
        ['type_id', ValidatorConstantsTypes::Int, ValidatorConstantsValidators::Optional],
        [ValidatorConstantsTypes::Email, ValidatorConstantsTypes::Email, ValidatorConstantsValidators::Optional],


        ];

    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function AllPaginationThisTableFilter(string $order_with_asc_desc = ''): void
    {
        [$tables, $cols] = $this->HandleThisTableJoins();
        $where_to_add = '';
        $where_val_to_add = [];
        if (! empty($_POST['record_date_from'])) {
            $record_date_from = $this->postValidator->Optional('record_date_from', ValidatorConstantsTypes::Date, $this->class_name . __LINE__);
            $record_date_from .= ' 00:00:00';
            $where_to_add .= ' AND `record_time` >= ?';
            $where_val_to_add[] = $record_date_from;
        }
        if (! empty($_POST['record_date_to'])) {
            $record_date_to = $this->postValidator->Optional('record_date_to', ValidatorConstantsTypes::Date, $this->class_name . __LINE__);
            $record_date_to .= ' 23:59:59';
            $where_to_add .= ' AND `record_time` <= ?';
            $where_val_to_add[] = $record_date_to;
        }
        if (! empty($_POST['sent_date_from'])) {
            $sent_date_from = $this->postValidator->Optional('sent_date_from', ValidatorConstantsTypes::Date, $this->class_name . __LINE__);
            $sent_date_from .= ' 00:00:00';
            $where_to_add .= ' AND `sent_time` >= ?';
            $where_val_to_add[] = $sent_date_from;
        }
        if (! empty($_POST['sent_date_to'])) {
            $sent_date_to = $this->postValidator->Optional('sent_date_to', ValidatorConstantsTypes::Date, $this->class_name . __LINE__);
            $sent_date_to .= ' 23:59:59';
            $where_to_add .= ' AND `sent_time` <= ?';
            $where_val_to_add[] = $sent_date_to;
        }
        $this->Pagination($tables, $cols, $where_to_add, $where_val_to_add);
    }

    public function CronEmailInitialize(): void
    {
        Json::Success(CronEmail::ALL_TYPES_NAME, line: $this->class_name . __LINE__);
    }
}