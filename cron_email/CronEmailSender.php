<?php
/**
 * @PHP       Version >= 8.2
 * @copyright ©2024 Maatify.dev
 * @author    Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since     2024-07-15 03:55 PM
 * @link      https://www.maatify.dev Maatify.com
 * @link      https://github.com/Maatify/CronEmail  view project on GitHub
 * @Maatify   DB :: CronEmail
 */

namespace Maatify\CronEmail;

use App\Assist\Encryptions\CronEmailEncryption;
use Maatify\Mailer\Mailer;
use Maatify\QueueManager\QueueManager;

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

    public function cronSend(): void
    {
        QueueManager::obj()->Email();
        $mailer = new Mailer();
        if($all = $this->notSent()){
            foreach ($all as $item){
                $mailer_sender = $mailer->reInitiateSender($item['email'], $item['name']);
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
                if($mailer_sender->$type($message, $item['subject'])){
                    $this->sentMarker($item[$this->identify_table_id_col_name]);
                }
            }
        }
    }
}