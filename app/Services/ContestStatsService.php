<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01/04/2020
 * Time: 17:54
 */

namespace App\Services;


use App\User;
use Carbon\Carbon;

class contestStatsService
{

    public function get_contest_statistics()
    {
    return [
        "topWeeklyReferrals"=>$this->get_top_referral_weekly(),
        "topMonthlyReferrals"=> $this->get_top_referral_monthly(),
        "topReferrals" => $this->get_top_referral()
    ];
    }


    /**
     * @return mixed
     */
    public function get_top_referral_weekly()
    {
        return  User::where("referrer_id","!=",null)->where("created_at",">", Carbon::now()->subWeek())->get();
    }

    /**
     * @return mixed
     */
    public function get_top_referral_monthly()
    {        return User::where("referrer_id","!=",null)->where("created_at",">", Carbon::now()->subMonth())->get();
     }


    /**
     * @return mixed
     */
    public function get_top_referral()
{        return User::where("referrer_id","!=",null)->get();
}


}
