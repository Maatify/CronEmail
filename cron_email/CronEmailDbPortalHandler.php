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

use App\Assist\AppFunctions;
use App\DB\DBS\DbPortalHandler;
use Maatify\Json\Json;
use Maatify\PostValidatorV2\ValidatorConstantsTypes;
use Maatify\Portal\Admin\AdminLoginToken;


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

    public function Record(): void
    {
        $_POST['admin_id'] = AdminLoginToken::obj()->GetAdminID();
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

    protected function Pagination(string $tables, string $cols, string $where_to_add, array $where_val_to_add): void
    {
        $result = $this->ArrayPaginationThisTableFilter($tables, $cols, $where_to_add, $where_val_to_add, " ORDER BY `$this->identify_table_id_col_name` ASC");
        if (! empty($result['data']) && $this->tableName == CronEmail::TABLE_NAME) {
            $result['data'] = array_map(function ($item) {
                $types = CronEmail::ALL_TYPES_NAME;
                $item['type_name'] = $types[$item['type_id']];

                return $item;
            }, $result['data']);
        }
        Json::Success(
            $result
        );
    }

    public function Remove(): void
    {
        $this->ValidatePostedTableId();
        $note = $this->postValidator->Optional('note', ValidatorConstantsTypes::Description, $this->class_name . __LINE__);
        $this->Delete("`$this->identify_table_id_col_name` = ? ", [$this->row_id]);
        $this->logger_keys = [$this->identify_table_id_col_name => $this->row_id];
        $logger[$this->identify_table_id_col_name] = $this->row_id;
        $changes = array();
        foreach ($this->current_row as $key => $value) {
            $logger_change = $logger[$key] = $value;

            $changes[] = [
                $key,
                $logger_change,
                '',
            ];
        }
        if (! empty($note)) {
            $logger['reason'] = $note;

            $changes[] = [
                'reason',
                '',
                $note,
            ];
        }
        $this->Logger($logger, $changes, 'Remove');

        Json::Success(line: $this->class_name . __LINE__);
    }

    public function AllPaginationThisTableFilterByTime(): void
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

        $this->Pagination($tables, $cols, $where_to_add, $where_val_to_add);
    }
}