<?php

namespace App\Services\Notification\Providers\Constants;

use App\Mail\ForgetPassword;
use App\Mail\TopicCreated;
use App\Mail\UserRegistered;

class EmailTypes
{
    const USER_REGISTERED = 1;
    const TOPIC_CREATED = 2;
    const FORGET_PASSWORD = 3;

    public static function toString(): array
    {
       $a = [
            self::USER_REGISTERED => 'ثبت نام کاربر',
            self::TOPIC_CREATED => 'ایجاد مطلب',
            self::FORGET_PASSWORD => 'فراموشی رمز عبور'
        ];
        dd($a);
    }

    public static function toMail($type)
    {
        try {
            return [
                self::USER_REGISTERED => UserRegistered::class,
                self::TOPIC_CREATED => TopicCreated::class,
                self::FORGET_PASSWORD => ForgetPassword::class
            ][$type];
        } catch (\Throwable $th) {
            throw new \InvalidArgumentException('Mailable class does not exist.');
        }
    }
}
