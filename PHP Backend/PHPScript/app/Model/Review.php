<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';
    protected $primaryKey = 'id';

      public function doctors()
    {      
        return $this->hasone('App\Model\Doctor', 'id', 'doctor_id');
    }
     public function users()
    {      
        return $this->hasone('App\User', 'id', 'user_id');
    }
     
}
?>