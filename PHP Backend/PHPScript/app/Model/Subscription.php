<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscription';
    protected $primaryKey = 'id';

     public function userdata()
    {      
        return $this->hasone('App\User', 'id', 'user_id');
    }

    public function packagedata()
    {      
        return $this->hasone('App\Model\Package', 'id', 'package_id');
    }

    public function paymentdata()
    {      
        return $this->hasone('App\Model\Paymentgateway', 'id', 'payment_type');
    }
   
}
?>