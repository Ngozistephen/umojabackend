<?php

namespace App\Listeners;

use Log;
use Illuminate\Support\Str;
use App\Events\VendorRegistered;
use Illuminate\Support\Facades\Mail;
use App\Mail\VendorPasswordSetupMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVendorPasswordSetupEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VendorRegistered $event): void
    {
        $passwordSetupToken = Str::random(60);
        // $vendorFirstName = $event->vendor->first_name;

       
       
        $event->vendor->update(['password_setup_token' => $passwordSetupToken]);

        $passwordSetupUrl = config('app.frontend_url') . '/auth/password_setup/' . $passwordSetupToken;

        
         Mail::to($event->vendor->email)->send(new VendorPasswordSetupMail($passwordSetupUrl));

        //  for testing
        // Mail::to('ngozi.stephen99@gmail.com')->send(new VendorPasswordSetupMail($passwordSetupUrl));
        // try {
        //     Mail::to('ngozi.stephen99@gmail.com')->send(new VendorPasswordSetupMail($passwordSetupUrl, $vendorFirstName ));
        // } catch (\Exception $e) {
        //     Log::error('Error sending email: ' . $e->getMessage());
        // }
    }
}
