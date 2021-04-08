<?php

namespace App\Http\Middleware;

use App\GraphQL\Errors\GraphqlError;
use Closure;

class PaystackRequestIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws GraphqlError
     */
    public function handle($request, Closure $next)
    {
        $paystackSignature = $request->header('X-Paystack-Signature');
        $input = $request->all();
        if (isset($paystackSignature)) {
            $hash = hash_hmac('sha512', json_encode($input), env('PAYSTACK_SECRET_KEY'));
            if($paystackSignature !== $hash){
                error_log($hash);
                error_log($paystackSignature);
                throw new GraphqlError("signature does not match.");
            }
        }else{
            error_log($paystackSignature);
            throw new GraphqlError("request does not include x-paystack-signature.");
        }
        return $next($request);
    }
}
