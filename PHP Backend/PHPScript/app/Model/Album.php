<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table = 'album';
    protected $primaryKey = 'id';

     public function gallerydata()
    {      
        return $this->hasmany('App\Model\Gallery', 'album_id', 'id');
    }
}
?>