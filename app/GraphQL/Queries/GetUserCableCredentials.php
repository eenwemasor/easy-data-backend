<?php

namespace App\GraphQL\Queries;

use App\CablePlanList;
use App\Repositories\APIRequests\ValidateMobileNgTransactionRepository;
use App\Repositories\APIRequests\ValidateTransactions;
use App\Services\CableTransactionService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetUserCableCredentials
{

    /**
     * @var ValidateMobileNgTransactionRepository
     */
    private $mobileNgTransactionRepository;
    /**
     * @var ValidateTransactions
     */
    private $validateTransactions;

    /**
     * GetUserBillCredentials constructor.
     * @param ValidateMobileNgTransactionRepository $mobileNgTransactionRepository
     * @param ValidateTransactions $validateTransactions
     */
    function __construct(ValidateMobileNgTransactionRepository $mobileNgTransactionRepository, ValidateTransactions $validateTransactions)
    {
        $this->mobileNgTransactionRepository = $mobileNgTransactionRepository;
        $this->validateTransactions = $validateTransactions;
    }

    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $cable_plan = CablePlanList::find($args['plan']);

        return   $this->mobileNgTransactionRepository->get_cable_card_details($args,$cable_plan->amount);
    }
}
