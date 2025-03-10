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

class CronEmailRecord extends CronEmail
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

    public function recordMessage(int $ct_id,string $name, string $email, string $message, string $subject): void
    {
        $this->addCron($ct_id, $name, $email, $message, $subject, self::TYPE_MESSAGE);
    }

    public function recordConfirmLink(int $ct_id,string $email, string $message): void
    {
        $this->addCron($ct_id, $email, $email, $message, 'Confirm Mail', self::TYPE_CONFIRM_URL);
    }

    public function recordConfirmCode(int $ct_id,string $email, string $code, $name = ''): void
    {
        if(empty($name)){
            $name = $email;
        }

        $this->addCron($ct_id, $name, $email, (new CronEmailEncryption())->Hash($code), 'Confirm Code', self::TYPE_CONFIRM_CODE);
    }

    public function recordTempPassword(int $ct_id,string $email, string $code, $name = ''): void
    {
        if(empty($name)){
            $name = $email;
        }

        $this->addCron($ct_id, $name, $email, (new CronEmailEncryption())->Hash($code), 'Your Temporary Password', self::TYPE_TEMP_PASSWORD);
    }

    public function recordAdminMessage(int $ct_id,string $name, string $email, string $message, string $subject): void
    {
        $this->addCron($ct_id, $name, $email, $message, $subject, self::TYPE_ADMIN_MESSAGE);
    }
}