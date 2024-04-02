<?php

namespace App\Http\Controllers\Admin;

use App\Enums\NewsLetterStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Client;
use App\Models\Newsletter;
use App\Models\NewsletterLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
include "/home/host1869952/test-newsletters.ru/htdocs/www/smsc_api.php";

class NewsLetterController extends Controller
{
    public function index(Request $request)
    {
        $newsletters = Newsletter::paginate($request->per_page);
        $statuses = NewsLetterStatusEnum::getAll();
        return Inertia::render('NewsLetters/Index', [
            'newsletters' => $newsletters,
            'statuses' => $statuses
        ]);
    }

    public function create()
    {
        $statuses = NewsLetterStatusEnum::getAll();

        return Inertia::render('NewsLetters/Edit', [
            'statuses' => $statuses
        ]);
    }

    public function store(Request $request)
    {
        $newsletter = Newsletter::create($request->all());

        return redirect(route('newsletters.edit', $newsletter));
    }

    public function update(Request $request, Newsletter $newsletter)
    {
        $newsletter = $newsletter->update($request->all());

        return redirect(route('newsletters.edit', $newsletter));
    }

    public function edit(Newsletter $newsletter)
    {
        $statuses = NewsLetterStatusEnum::getAll();

        return Inertia::render('NewsLetters/Edit', [
            'newsletter' => $newsletter,
            'statuses' => $statuses
        ]);
    }

    public function destroy($id)
    {
        $newsletter = Newsletter::findOrFail($id);

        $newsletter->delete();

        return redirect(route('newsletters.index'));
    }

    public function start(Request $request) {
        $newsletter = Newsletter::findOrFail($request->params['id']);
        $newsletter->update(['status' => NewsLetterStatusEnum::ACTIVE]);
        $clients = Client::all();
        foreach ($clients as $client) {
            $newsletter->clients()->syncWithPivotValues($client->id, [
                'status' => 'waiting'
            ]);
        }
        if ($newsletter->immediate_send) {
            foreach ($clients as $client) {
                list($sms_id, $sms_cnt, $cost, $balance) = send_sms($client->phone_number, $newsletter->text, 0, 0, 0, 3, false);
                list($status, $time) = get_status($sms_id, $client->phone_number);
                if ($status == 1 || $status == -1) {
                    $newsletter->clients()->syncWithPivotValues($client->id, [
                        'status' => 'delivered'
                    ]);
                } else {
                    $newsletter->clients()->syncWithPivotValues($client->id, [
                        'status' => 'unsuccessful'
                    ]);
                }
            }
            if ($newsletter->clients()->where('status', 'waiting')->count() == 0) {
                $newsletter->update(['status' => NewsLetterStatusEnum::FINISHED]);
            }
        }
        return redirect(route('newsletters.edit', $newsletter));
    }

    public function stop(Request $request) {
        $newsletter = Newsletter::findOrFail($request->params['id']);
        $newsletter->update(['status' => NewsLetterStatusEnum::STOPPED]);
        return redirect(route('newsletters.edit', $newsletter));
    }

    public function continueSend(Request $request) {
        $newsletter = Newsletter::findOrFail($request->params['id']);
        $newsletter->update(['status' => NewsLetterStatusEnum::ACTIVE]);
        $clients = $newsletter->clients()->where('status', 'waiting')->get();
        if ($newsletter->immediate_send) {
            foreach ($clients as $client) {
                list($sms_id, $sms_cnt, $cost, $balance) = send_sms($client->phone_number, $newsletter->text, 0, 0, 0, 3, false);
                list($status, $time) = get_status($sms_id, $client->phone_number);
                if ($status == 1 || $status == -1) {
                    $newsletter->clients()->syncWithPivotValues($client->id, [
                        'status' => 'delivered'
                    ]);
                } else {
                    $newsletter->clients()->syncWithPivotValues($client->id, [
                        'status' => 'unsuccessful'
                    ]);
                }
            }
        }
        if ($newsletter->clients()->where('status', 'waiting')->count() == 0) {
            $newsletter->update(['status' => NewsLetterStatusEnum::FINISHED]);
        }
        return redirect(route('newsletters.edit', $newsletter));
    }
}
