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
use App\Assist\Encryptions\CronEmailEncryption;
use App\DB\Tables\Queue;
use Maatify\Mailer\Mailer;

class CronEmailSender extends CronEmail
{
    private static self $instance;

    public static function obj(): self
    {
        if(empty(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function SentMarker(int $cron_id): void
    {
        $this->Edit([
            'status'     => 1,
            'sent_time'   => AppFunctions::CurrentDateTime(),
        ], "`$this->identify_table_id_col_name` = ? ", [$cron_id]);
    }

    private function NotSent(): array
    {
        return $this->RowsThisTable('*', '`status` = ? ', [0]);
    }

    protected function NotSentByType(int $type_id): array
    {
        return $this->RowsThisTable('*', "`status` = ? AND `type_id` = ? ORDER BY `$this->tableName`.`$this->identify_table_id_col_name` ASC LIMIT 10", [0, $type_id]);
    }



    private function NotSentConfirmCode(): array
    {
        return $this->NotSentByType(self::TYPE_CONFIRM_CODE);
    }

    private function NotSentPasswords(): array
    {
        return $this->NotSentByType(self::TYPE_TEMP_PASSWORD);
    }

    private function NotSentConfirmUrl(): array
    {
        return $this->NotSentByType(self::TYPE_CONFIRM_URL);
    }

    private function NotSentAdminMessage(): array
    {
        return $this->NotSentByType(self::TYPE_ADMIN_MESSAGE);
    }

    private function NotSentMessage(): array
    {
        return $this->NotSentByType(self::TYPE_MESSAGE);
    }

    private function NotSentPromotion(): array
    {
        return $this->NotSentByType(self::TYPE_PROMOTION);
    }

    private function NotSentEmail(): array
    {
        if(!$list = $this->NotSentConfirmCode()){
            if(!$list = $this->NotSentPasswords()){
                if(!$list = $this->NotSentConfirmUrl()){
                    if(!$list = $this->NotSentAdminMessage()){
                        if(!$list = $this->NotSentMessage()){
                            $list = $this->NotSentPromotion();
                        }
                    }
                }
            }
        }
        return $list;
    }

    public function CronSend(): void
    {
        Queue::obj()->Email();
        if($all = $this->NotSentEmail()){
            foreach ($all as $item){

                $mailer = new Mailer($item['email'], $item['name']);
                $message = $item['message'];
                switch ($item['type_id']){

                    case self::TYPE_MESSAGE;
                        $type = 'Message';
                        break;

                    case self::TYPE_CONFIRM_URL;
                        $type = 'ConfirmCustomerLink';
                        break;

                    case self::TYPE_CONFIRM_CODE;
                        $type = 'ConfirmCode';
                        $message = (new CronEmailEncryption())->DeHashed($item['message']);
                        break;

                    case self::TYPE_TEMP_PASSWORD;
                        $type = 'TempPassword';
                        $message = (new CronEmailEncryption())->DeHashed($item['message']);
                        break;

                    case self::TYPE_ADMIN_MESSAGE;
                        $type= 'AdminMessage';
                        break;

                    default;
                        $type = 'Message';
                }
                if($mailer->$type($message, $item['subject'])){
                    $this->SentMarker($item[$this->identify_table_id_col_name]);
                }
            }
        }
    }
}