<?php

namespace App\GraphQL\Queries;

use App\CablePlanList;
use App\GraphQL\Errors\GraphqlError;
use App\PowerPlanList;
use App\Repositories\APIRequests\ValidateTransactions;
use App\Services\CableTransactionService;
use App\Services\QuickBuyService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetUserBillCredentials
{
    /**
     * @var ValidateTransactions
     */
    private $validateTransactions;

    /**
     * GetUserBillCredentials constructor.
     * @param ValidateTransactions $validateTransactions
     */
    function __construct(ValidateTransactions $validateTransactions)
    {
        $this->validateTransactions = $validateTransactions;
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
        $api_wallet = $this->validateTransactions->get_api_account_info();
        $plan = PowerPlanList::find($args['plan']);

        if ($api_wallet < $args['amount']) {
            throw new GraphqlError("Service is not available currently, please try again later");
        }

        $available_services = $this->validateTransactions->get_available_services('ELECT');

        QuickBuyService::checkAvailableService($available_services, $plan->disco, $args['type'],$plan->description);


        return $this->validateTransactions->get_bills_meter_details($args, $args['amount']);
    }
}
