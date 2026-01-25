<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;

class CartPolicy
{
    /**
     * Determine whether the user can view the cart item.
     */
    public function view(User $user, Cart $cart): bool
    {
        // Customer can only view their own cart items
        return $cart->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the cart item.
     */
    public function update(User $user, Cart $cart): bool
    {
        // Customer can only update their own cart items
        return $cart->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the cart item.
     */
    public function delete(User $user, Cart $cart): bool
    {
        // Customer can only delete their own cart items
        return $cart->user_id === $user->id;
    }
}
