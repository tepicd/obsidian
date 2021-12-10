<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function sendEmail()
    {

    	dispatch(new SendEmailJob($data))->delay(now()->addSeconds(2));

    	dd('Email delivered');
    }
}
