<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LockerInventoryLog extends Model
{
   
	public function user ()
	{
		return $this->belongsTo("App\Models\User");
	}

	public function locker ()
	{
		return $this->belongsTo("App\Models\LockerInventory");
	}

	public function oldStudent ()
	{
		return $this->belongsTo("App\Models\Student", "old_student_no", "studentnumber");
	}

	public function newStudent ()
	{
		return $this->belongsTo("App\Models\Student", "new_student_no", "studentnumber");
	}

}
