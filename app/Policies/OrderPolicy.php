<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        // Customer can only view their own orders
        if ($user->isCustomer()) {
            return $order->customer_id === $user->id;
        }

        // Admin and Owner can view any order
        return $user->isAdmin() || $user->isOwner();
    }

    /**
     * Determine whether the user can upload payment for the order.
     */
    public function uploadPayment(User $user, Order $order): bool
    {
        // Only the customer who made the order can upload payment
        return $user->isCustomer() && $order->customer_id === $user->id;
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        // Only the customer who made the order can cancel
        return $user->isCustomer() && $order->customer_id === $user->id;
    }

    /**
     * Determine whether the user can confirm received the order.
     */
    public function confirmReceived(User $user, Order $order): bool
    {
        // Only the customer who made the order can confirm receipt
        return $user->isCustomer() && $order->customer_id === $user->id;
    }
}
