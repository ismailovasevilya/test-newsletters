<?php

namespace App\Jobs;

use App\Models\NewsLetter;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessNewsletters implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected object $newsLetter;
    protected $users;
    /**
     * Create a new job instance.
     */
    public function __construct($newsLetter, $users)
    {
        $this->newsLetter = $newsLetter;
        $this->users = $users;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
//        $ch = curl_init();
//        $sent = 0;
//        $received = 0;
//
//        foreach($this->users as $user) {
//
//            TgUserNewsletter::create([
//                'tg_user_id' =>  $user['user_id'],
//                'segment_id' => $this->newsLetter->segment,
//                'created_at' => now()
//            ]);
//
//            $data = [
//                'chat_id' => $user['user_id'],
//                'parse_mode' =>'HTML',
//                'disable_web_page_preview' => true,
//            ];
//            if ($user['language'] == 'ru') {
//                $data['text'] = $this->newsLetter->message_ru;
//            }
//            else {
//                $data['text'] = $this->newsLetter->message_uz;
//            }
//            $sent = $sent + 1;
//            try {
//                curl_setopt($ch, CURLOPT_URL,"");
//                curl_setopt($ch, CURLOPT_POST, 1);
//                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: keep-alive'));
//                curl_setopt($ch, CURLOPT_POSTFIELDS,
//                    http_build_query($data)
//                );
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//                $server_output = curl_exec($ch);
//                if (json_decode($server_output)->ok == true) {
//                    $received = $received + 1;
//                }
//            } catch (Exception $e) {
//                if ($sent == $this->newsLetter->total) {
//                    $this->newsLetter->update(['status' => NewsLetterStatusEnum::DONE]);
//                }
//            }
//        }
//        curl_close ($ch);
//        NewsLetter::find($this->newsLetter->id)->increment('user_sent', $sent);
//        NewsLetter::find($this->newsLetter->id)->increment('user_received', $received);
//
//        if (Carbon::now()->format('Y-m-d H:i:00') >= Carbon::today()->format('Y-m-d 18:25:00')) {
//            exit;
//        }
//
//        if ($this->newsLetter->user_sent == $this->newsLetter->total) {
//            $this->newsLetter->update(['status' => NewsLetterStatusEnum::DONE]);
//        }
//
    }
}
