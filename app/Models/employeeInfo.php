<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employeeInfo extends Model
{  use HasFactory;
    protected $table = 'employee_infos';
    protected $primaryKey = 'employee_id';
    protected $fillable = [
        'email',
        'phone_number',
        'address',
        'fullname',
        'emp_branch',
    ];
    public $timestamps = false;
    public function employee_showroom(){
        return $this->belongsTo(showroom::class,'emp_branch');
    }
    public function employee_Account()
    {
        
        return $this->hasOne(employeeAccount::class,'email','email');
        
    }
}
