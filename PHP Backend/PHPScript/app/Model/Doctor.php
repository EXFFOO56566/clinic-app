<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors';
    protected $primaryKey = 'id';

     public function department()
    {      
        return $this->hasone('App\Model\Department', 'id', 'department_id');
    }

    public function TimeTabledata(){
    	return $this->hasmany('App\Model\TimeTable',"doctor_id","user_id");
    }
    


 public function rattingdata()
    {      
        return $this->hasmany('App\Model\Review', 'doctor_id', 'id');
    }
    
}
?>