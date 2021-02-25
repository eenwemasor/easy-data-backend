<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27/03/2020
 * Time: 01:00
 */

namespace App\Repositories;


use App\Contracts\CreateUserContract;
use App\GraphQL\Errors\GraphqlError;
use App\User;

class CreateUserRepository implements CreateUserContract
{

    /**
     * @param array $user
     * @return User
     */
    public function create(array $user): User
    {
        return  User::create($user);
    }

    /**
     * @param string $user_id
     * @return User
     */
    public function update(string $user_id): User
    {
        // TODO: Implement update() method.
    }

    /**
     * @param string $user_id
     * @return User
     */
    public function delete(string $user_id): User
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param string $user_id
     * @return User
     * @throws
     */
    public function create_transaction_pin(string $user_id): User
    {
       $user = User::find($user_id);
       if(isset($user->transaction_pin)){
            throw new GraphqlError("You already have transaction pin Associated with your account");
       }else{
           $random_transaction_pin = rand(1000,9999);
           $random_transaction_pin_exists = User::where('transaction_pin', $random_transaction_pin);

           if(!$random_transaction_pin_exists){
               throw new GraphqlError("Error");
           }else{
               $user->transaction_pin = $random_transaction_pin;
              $user->save();

               return $user;
           }

       }

}

    /**
     * @param string $user_id
     * @param string $current_transaction_pin
     * @param string $new_transaction_pin
     * @return User
     * @throws GraphqlError
     */
    public function update_transaction_pin(string $user_id,string $current_transaction_pin,string $new_transaction_pin): User
    {
        $user = User::find($user_id);
        if(isset($user->transaction_pin)){
            if($user->transaction_pin === $current_transaction_pin){
                $random_transaction_pin = $new_transaction_pin;
            $random_transaction_pin_exists = User::where('transaction_pin', $random_transaction_pin);

            if(!$random_transaction_pin_exists){
                throw new GraphqlError("Error created transaction pin");
            }else{
    $user->transaction_pin = $random_transaction_pin;
    $user->save();
    return $user;
}
}else{
                throw new GraphqlError("Incorrect pin");
            }
        }else{
            throw new GraphqlError("No Transaction Associated to your account");


        }
    }
}
