<?php

namespace App\Console\Commands;

use App\Enums\NewsLetterStatusEnum;
use App\Models\Newsletter;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

include "/home/host1869952/test-newsletters.ru/htdocs/www/smsc_api.php";

class RunNewsLetters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run-news-letters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск рассылки';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $newsLetters = Newsletter::with('clients')
            ->where('status', NewsLetterStatusEnum::ACTIVE)
            ->where('time_to_send', Carbon::now()->startOfMinute()->format('H:i'))
            ->get();

        $today = Carbon::today()->addDays(7);
        $day = $today->day;
        $month = $today->month;

        foreach ($newsLetters as $newsLetter) {
            $clients = $newsLetter->clients()->whereRaw('DAY(birth_date) = ? AND MONTH(birth_date) = ?', [$day, $month])->get();
            foreach ($clients as $client) {
                try {
                    list($sms_id, $sms_cnt, $cost, $balance) = send_sms($client->phone_number, $newsLetter->text, 0, 0, 0, 3, false);
                    list($status, $time) = get_status($sms_id, $client->phone_number);
                    if ($status == 1 || $status == -1) {
                        $newsLetter->clients()->syncWithPivotValues($client->id, [
                            'status' => 'delivered'
                        ]);
                    } else {
                        $newsLetter->clients()->syncWithPivotValues($client->id, [
                            'status' => 'unsuccessful'
                        ]);
                    }
                    if ($newsLetter->clients()->where('status', 'waiting')->count() == 0) {
                        $newsLetter->update(['status' => NewsLetterStatusEnum::FINISHED]);
                    }
                }
                catch (\Exception $e) {
                    Log::info($e->getMessage());
                }
            }
        }
    }
}