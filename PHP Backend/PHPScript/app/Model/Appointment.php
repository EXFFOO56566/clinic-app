<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointment';
    protected $primaryKey = 'id';

      public function doctors()
    {      
        return $this->hasone('App\User', 'id', 'doc_id');
    }
     public function services()
    {      
        return $this->hasone('App\Model\DepartService', 'id', 'service_id');
    }
     public function department()
    {      
        return $this->hasone('App\Model\Department', 'id', 'department_id');
    }

    public function referdocto(){
       return $this->hasone('App\User', 'id', 'refer_to');
    }

    public function referdocby(){
       return $this->hasone('App\User', 'id', 'refer_by');
    }
}
?>