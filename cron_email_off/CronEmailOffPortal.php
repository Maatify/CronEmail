<?php
/**
 * @PHP       Version >= 8.0
 * @copyright ©2024 Maatify.dev
 * @author    Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since     2024-07-17 10:19 AM
 * @link      https://www.maatify.dev Maatify.com
 * @link      https://github.com/Maatify/CronEmail  view project on GitHub
 * @Maatify   DB :: CronEmail
 */

namespace Maatify\CronEmailOff;

use Maatify\CronEmail\CronEmailDbPortalHandler;
use Maatify\PostValidatorV2\ValidatorConstantsTypes;
use Maatify\PostValidatorV2\ValidatorConstantsValidators;

class CronEmailOffPortal extends CronEmailDbPortalHandler
{
    const TABLE_NAME                 = CronEmailOff::TABLE_NAME;
    const TABLE_ALIAS                = CronEmailOff::TABLE_ALIAS;
    const IDENTIFY_TABLE_ID_COL_NAME = CronEmailOff::IDENTIFY_TABLE_ID_COL_NAME;
    const LOGGER_TYPE                = CronEmailOff::LOGGER_TYPE;
    const LOGGER_SUB_TYPE            = CronEmailOff::LOGGER_SUB_TYPE;
    const Cols                       = CronEmailOff::Cols;

    protected string $tableName = self::TABLE_NAME;
    protected string $tableAlias = self::TABLE_ALIAS;
    protected string $identify_table_id_col_name = self::IDENTIFY_TABLE_ID_COL_NAME;
    protected array $cols = self::Cols;
    private static self $instance;

    protected array $cols_to_add = [
        ['ct_id', ValidatorConstantsTypes::Int, ValidatorConstantsValidators::Require],
        [ValidatorConstantsTypes::Description, ValidatorConstantsTypes::Description, ValidatorConstantsValidators::Require],
        [ValidatorConstantsTypes::Email, ValidatorConstantsTypes::Email, ValidatorConstantsValidators::Require],
    ];

    protected array $cols_to_filter = [
        [self::IDENTIFY_TABLE_ID_COL_NAME, ValidatorConstantsTypes::Int, ValidatorConstantsValidators::Optional],
        ['ct_id', ValidatorConstantsTypes::Int, ValidatorConstantsValidators::Optional],
        [ValidatorConstantsTypes::Description, ValidatorConstantsTypes::Description, ValidatorConstantsValidators::Optional],
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
        if (! empty($_POST['date_from'])) {
            $record_date_from = $this->postValidator->Optional('date_from', ValidatorConstantsTypes::Date, $this->class_name . __LINE__);
            $record_date_from .= ' 00:00:00';
            $where_to_add .= ' AND `time` >= ?';
            $where_val_to_add[] = $record_date_from;
        }
        if (! empty($_POST['date_to'])) {
            $record_date_to = $this->postValidator->Optional('date_to', ValidatorConstantsTypes::Date, $this->class_name . __LINE__);
            $record_date_to .= ' 23:59:59';
            $where_to_add .= ' AND `time` <= ?';
            $where_val_to_add[] = $record_date_to;
        }
        if(!empty($_POST['user_id'])){
            $admin_id = $this->postValidator->Optional('user_id', ValidatorConstantsTypes::Int, $this->class_name . __LINE__);
            $where_to_add .= ' AND `admin_id` = ?';
            $where_val_to_add[] = $admin_id;
        }

        $this->pagination($tables, $cols, $where_to_add, $where_val_to_add);
    }
}