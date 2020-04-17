<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27/03/2020
 * Time: 00:58
 */

namespace App\Contracts;


use App\User;

interface CreateUserContract
{

    /**
     * @param array $user
     * @return User
     */
    public function create(array $user):User;

    /**
     * @param string $user_id
     * @return User
     */
    public function update(string $user_id):User;

    /**
     * @param string $user_id
     * @return User
     */
    public function create_transaction_pin(string $user_id):User;

    /**
     * @param string $user_id
     * @param string $current_transaction_pin
     * @param string $new_transaction_pin
     * @return User
     */
    public function update_transaction_pin(string $user_id, string $current_transaction_pin, string $new_transaction_pin):User;


    /**
     * @param string $user_id
     * @return User
     */
    public function delete(string $user_id):User;

}