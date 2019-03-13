<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use Carbon\Carbon;

class SendEmailController extends Controller
{
    /**
     * Simulate sending the email.
     *
     * @return mixed
     */
    public function simulate()
    {
        $emailJob = (new SendEmailJob('chantouchsek.cs83@gmail.com'))->delay(Carbon::now()->addSeconds(3));

        dispatch($emailJob);

        return $this->respondCreated('Email has been sent.');
    }
}
