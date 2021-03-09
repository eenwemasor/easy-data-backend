<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31/03/2020
 * Time: 14:09
 */

namespace App\Services;


use App\AdminChannelUtil;
use App\Events\TalkToUsMessageEvent;

class TalkToUsMessageService
{
    /**
     * @param array $data
     * @return bool
     */
    public function forward_mail( array $data){
        $admin = AdminChannelUtil::first();

        $name = $data['name'];
        $email= $data['email'];
        $message = $data['message'];

        event(new TalkToUsMessageEvent($admin->email,$name, $email,$message));
        return true;
}
}
