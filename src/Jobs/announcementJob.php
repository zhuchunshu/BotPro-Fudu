<?php

namespace App\Plugins\fudu\src\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class announcementJob implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $group;

    public $content;

    public $time;

    public $timeout = 590;

    public function __construct($group,$content,$time=null)
    {
        $this->group = $group;
        $this->content = $content;
        $this->time = $time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->group as $value) {
            sendMsg([
                'group_id' => $value,
                'content' => $this->content
            ], "_send_group_notice");
            if($this->time){
                sleep($this->time);
            }
        }
    }

}