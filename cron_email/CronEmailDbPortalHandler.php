<?php
/**
 * @PHP       Version >= 8.2
 * @copyright ©2024 Maatify.dev
 * @author    Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since     2024-07-17 11:28 AM
 * @link      https://www.maatify.dev Maatify.com
 * @link      https://github.com/Maatify/CronEmail  view project on GitHub
 * @Maatify   DB :: CronEmail
 */

namespace Maatify\CronEmail;

use App\Assist\AppFunctions;
use App\DB\DBS\DbPortalHandler;
use JetBrains\PhpStorm\NoReturn;
use Maatify\Json\Json;
use Maatify\PostValidatorV2\ValidatorConstantsTypes;


class CronEmailDbPortalHandler extends DbPortalHandler
{
    private static self $instance;

    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function record(): void
    {
        $_POST['admin_id'] = AppFunctions::obj()->getCurrentAdminId();
        $_POST['time'] = AppFunctions::CurrentDateTime();
        $email = $this->postValidator->Require(ValidatorConstantsTypes::Email, ValidatorConstantsTypes::Email, $this->class_name . __LINE__);
        $this->jsonCheckEmailExist($email);
        parent::Record();
    }

    private function jsonCheckEmailExist(string $email): void
    {
        if($this->RowIsExistThisTable(' LOWER(`email`) = ? ', [strtolower($email)])) {
            Json::Exist('email', $email . ' Already Exists', $this->class_name . __LINE__);
        }
    }

    #[NoReturn] protected function pagination(string $tables, string $cols, string $where_to_add, array $where_val_to_add): void
    {
        $result = $this->ArrayPaginationThisTableFilter($tables, $cols, $where_to_add, $where_val_to_add, " ORDER BY `$this->identify_table_id_col_name` ASC");
        if (! empty($result['data']) && $this->tableName == CronEmail::TABLE_NAME) {
            $result['data'] = array_map(function ($item) {
                $types = CronEmail::ALL_TYPES_NAME;
                $item['type_name'] = $types[$item['type_id']];
                if(in_array($item['type_id'], [CronEmail::TYPE_CONFIRM_CODE, CronEmail::TYPE_TEMP_PASSWORD])){
                    $item['message'] = "{Encrypted}";
                }
                return $item;
            }, $result['data']);
        }
        Json::Success(
            $result
        );
    }

    #[NoReturn] public function remove(): void
    {
        $this->ValidatePostedTableId();
        $note = $this->postValidator->Optional('note', ValidatorConstantsTypes::Description, $this->class_name . __LINE__);
        $this->Delete("`$this->identify_table_id_col_name` = ? ", [$this->row_id]);
        $this->logger_keys = [$this->identify_table_id_col_name => $this->row_id];
        $logger[$this->identify_table_id_col_name] = $this->row_id;
        $changes = array();
        foreach ($this->current_row as $key => $value) {
            $logger_change = $logger[$key] = $value;

            $changes[$key] = $logger_change;
        }
        if (! empty($note)) {
            $logger['reason'] = $note;

            $changes['reason'] = $note;
        }
        $this->Logger($logger, $changes, 'Remove');

        Json::Success(line: $this->class_name . __LINE__);
    }

    #[NoReturn] public function allPaginationThisTableFilterByTime(): void
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

        $this->pagination($tables, $cols, $where_to_add, $where_val_to_add);
    }
}