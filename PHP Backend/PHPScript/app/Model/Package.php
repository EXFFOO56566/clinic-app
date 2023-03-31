<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'price_package';
    protected $primaryKey = 'id';

    public function deparmentdata(){
    	return $this->hasone('App\Model\Department', 'id', 'department_id');
    }
     
}
?>