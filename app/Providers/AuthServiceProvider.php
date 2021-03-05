<?php

namespace App\Providers;

use App\AccountLevel;
use App\AccountLevelApplicable;
use App\AdminChannelUtil;
use App\AirtimeTransaction;
use App\BankAccount;
use App\BulkSMS;
use App\CablePlanList;
use App\CableTransaction;
use App\DataPlanList;
use App\DataTransaction;
use App\ElectricityTransaction;
use App\NewsFeed;
use App\NewsUpdate;
use App\Policies\AccountLevelApplicablePolicy;
use App\Policies\AccountLevelPolicy;
use App\Policies\AdminChannelUtilPolicy;
use App\Policies\AirtimeTransactionPolicy;
use App\Policies\BankAccountPolicy;
use App\Policies\BulkSMSPolicy;
use App\Policies\CablePlanListPolicy;
use App\Policies\CableTransactionPolicy;
use App\Policies\DataPlanListPolicy;
use App\Policies\DataTransactionPolicy;
use App\Policies\ElectricityTransactionPolicy;
use App\Policies\NewsFeedPolicy;
use App\Policies\NewsUpdatePolicy;
use App\Policies\PowerPlanListPolicy;
use App\Policies\ReferralRewardPolicy;
use App\Policies\ResultCheckerPolicy;
use App\Policies\ResultCheckTransactionPolicy;
use App\Policies\WalletTransactionPolicy;
use App\PowerPlanList;
use App\ReferralReward;
use App\ResultChecker;
use App\ResultCheckTransaction;
use App\WalletTransaction;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        AdminChannelUtil::class => AdminChannelUtilPolicy::class,
        AirtimeTransaction::class => AirtimeTransactionPolicy::class,
        CablePlanList::class =>CablePlanListPolicy::class,
        CableTransaction::class => CableTransactionPolicy::class,
        DataPlanList::class => DataPlanListPolicy::class,
        DataTransaction::class=>DataTransactionPolicy::class,
        ElectricityTransaction::class => ElectricityTransactionPolicy::class,
        NewsFeed::class  => NewsFeedPolicy::class,
        NewsUpdate::class =>NewsUpdatePolicy::class,
        PowerPlanList::class =>PowerPlanListPolicy::class,
        ReferralReward::class =>ReferralRewardPolicy::class,
        WalletTransaction::class =>WalletTransactionPolicy::class,
        BankAccount::class => BankAccountPolicy::class,
        BulkSMS::class => BulkSMSPolicy::class,
        AccountLevel::class => AccountLevelPolicy::class,
        AccountLevelApplicable::class => AccountLevelApplicablePolicy::class,
        ResultCheckerPolicy::class => ResultChecker::class,
        ResultCheckTransactionPolicy::class => ResultCheckTransaction::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        //
    }
}
