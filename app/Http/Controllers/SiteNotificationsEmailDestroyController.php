<?php

namespace App\Http\Controllers;


use App\Models\Site;
use Illuminate\Http\Request;

class SiteNotificationsEmailDestroyController extends Controller
{
    
    public function __invoke(Request $request, Site $site)
    {

       

        $site->update([
            'notification_emails' => array_diff(
                $site->notification_emails, [$request->email]
            )
            ]);

            return back();
    }
}
