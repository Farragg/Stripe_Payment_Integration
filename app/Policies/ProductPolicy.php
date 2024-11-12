<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update()
    {
        // allow deletion for all authenticated users
        return true;
    }

    public function delete()
    {
        // allow updates for all authenticated users
        return true;
    }

}
