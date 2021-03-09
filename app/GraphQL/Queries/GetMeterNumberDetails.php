<?php

namespace App\GraphQL\Queries;

use App\GraphQL\Errors\GraphqlError;
use App\PowerPlanList;
use App\Vendors\MobileNg\MobileNgElectricity;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetMeterNumberDetails
{
    /**
     * @var ValidateTransactions
     */
    private $mobileNgElectricity;

    function __construct(MobileNgElectricity $mobileNgElectricity)
    {
        $this->mobileNgElectricity = $mobileNgElectricity;
    }

    /**
     * Return a value for the field.
     *
     * @param  null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[] $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     * @throws
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
//        $api_wallet = $this->mobileNgElectricity->get_api_account_info();
//        $plan = PowerPlanList::find($args['plan']);
//
//        if ($api_wallet->balance < $args['amount']) {
//            throw new GraphqlError("Service is not available currently, please try again later");
//        }
//
//        $available_services = $this->mobileNgElectricity->get_available_services('ELECT');
//
//        ValidateTransactions::checkAvailableService($available_services, $plan->disco, $args['type'],$plan->description);


//        return $this->mobileNgElectricity->get_bills_meter_details($args);
        return null;
    }
}
