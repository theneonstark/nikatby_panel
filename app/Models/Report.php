<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    // Defines which attributes are mass-assignable.
    protected $fillable = ['number','mobile','provider_id','api_id','amount','bankcharge','charge','profit','gst','tds','apitxnid','txnid','payid','refno','description','remark','option1','option2','option3','option4','option5','option6','option7','option8','status','user_id','credit_by','rtype','via','balance','closing','trans_type','product','create_time', 'transfer_mode','reversed_at'];

    // Specifies additional attributes to append to the model.
    public $appends = ["fundbank", 'apicode', 'apiname', 'username', 'sendername', 'providername'];

    // Relationship with the User model via 'user_id'.
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    // Relationship with the User model via 'credit_by' for sender details.
    public function sender(){
        return $this->belongsTo('App\Models\User', 'credit_by');
    }

    // Relationship with the Api model via 'api_id'.
    public function api(){
        return $this->belongsTo('App\Models\Api');
    }

    // Relationship with the Provider model via 'provider_id'.
    public function provider(){
        return $this->belongsTo('App\Models\Provider');
    }

    // Formats the 'updated_at' attribute before returning it.
    public function getUpdatedAtAttribute($value)
    {
        return date('d M y - h:i A', strtotime($value));
    }

    // Formats the 'created_at' attribute before returning it.
    public function getCreatedAtAttribute($value)
    {
        return date('d M - H:i', strtotime($value));
    }

    // Rounds the 'amount' attribute to two decimal places.
    public function getAmountAttribute($value)
    {
        return round($value, 2);
    }

    // Retrieves associated fund bank details if the product is 'fund request'.
    // public function getFundbankAttribute($value)
    // {
    //     $data = '';
    //     if($this->product == "fund request"){
    //         $data = Fundbank::where('id', $this->option1)->first();
    //     }
    //     return $data;
    // }

    // Retrieves the 'code' of the associated API.
    public function getApicodeAttribute()
    {
        $data = ApiManagement::where('id' , $this->api_id)->first(['code']);
        return $data->code;
    }

    // Retrieves the 'name' of the associated API.
    public function getApinameAttribute()
    {
        $data = ApiManagement::where('id' , $this->api_id)->first(['name']);
        return $data->name;
    }

    // Retrieves the name of the associated provider or returns "Not Found".
    // public function getProvidernameAttribute()
    // {
    //     $data = '';
    //     if($this->provider_id){
    //         $provider = Provider::where('id' , $this->provider_id)->first(['name']);
    //         if($provider){
    //             $data = $provider->name;
    //         }else{
    //             $data = "Not Found";
    //         }
    //     }
    //     return $data;
    // }

    // Retrieves the user's name, ID, and role.
    public function getUsernameAttribute()
    {
        $data = '';
        if($this->user_id){
            $user = \App\Models\User::where('id' , $this->user_id)->first(['name', 'id', 'role_id']);
            $data = $user->name." (".$user->id.") <br>(".$user->role->name.")";
        }
        return $data;
    }

    // Retrieves the sender's name, ID, and role.
    public function getSendernameAttribute()
    {
        $data = '';
        if($this->credit_by){
            $user = \App\Models\User::where('id' , $this->credit_by)->first(['name', 'id', 'role_id']);
            $data = $user->name." (".$user->id.")<br>(".$user->role->name.")";
        }
        return $data;
    }
    
    // Removes special characters from 'refno'.
    public function getRefnoAttribute($value)
    {
        return str_replace('"', "", str_replace(",","",$value));
    }
    
    // Removes special characters from 'remark'.
    public function getRemarkAttribute($value)
    {
        return str_replace('"',"",$value);
    }
}
