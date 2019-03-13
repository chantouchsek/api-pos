<?php

namespace App\Http\Controllers;


use App\Jobs\SendOrderEmail;

class SendEmailController extends Controller
{
    /**
     * Simulate sending the email.
     *
     * @return mixed
     */
    public function simulate()
    {
        SendOrderEmail::dispatch();

        return $this->respondCreated('Email has been sent.');
    }
}
