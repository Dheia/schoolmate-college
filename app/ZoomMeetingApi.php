<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZoomMeetingApi extends Model
{
    //
    protected $table = 'zoom_api_credentials';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = [
	    'access_token',
        'token_type',
        'expires_in',
        'scope',
	];

}
