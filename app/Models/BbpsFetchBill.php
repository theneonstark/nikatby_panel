<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Model;

   class BbpsFetchBill extends Model
   {
       protected $table = 'bbps_fetch_bill';

          protected $fillable = [
        'biller_id',
        'request_id',
        'api_response',
    ];
}