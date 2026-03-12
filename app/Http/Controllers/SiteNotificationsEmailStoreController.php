<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use Illuminate\Support\Arr;

use App\Http\Requests\SiteNotificationsEmailStoreRequest;

class SiteNotificationsEmailStoreController extends Controller
{
    public function __invoke(SiteNotificationsEmailStoreRequest $request, Site $site)
    {
        $emails = $site->notification_emails ?? [];
        array_unshift($emails, $request->input('email'));

        $site->update([
            'notification_emails' => $emails,
        ]);


         return back();
    }
}
