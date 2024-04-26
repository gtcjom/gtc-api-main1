<?php

namespace App\Jobs;

use App\Models\CloudUnSent;
use App\Services\Cloud\SendToCloudService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRetryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $unSents = CloudUnSent::query()->get();

        if($unSents->count() == 0){
            return;
        }

        $service = new SendToCloudService();

        foreach ($unSents as $unsent){

            switch ($unsent->type){
                case 'patient':

                    if($unsent->category == 'update')
                    $service->updatePatient($unsent->type_id);

                    $unsent->delete();

                    if($unsent->category == 'create')
                    $service->createPatient($unsent->type_id);
                    $unsent->delete();
                    if($unsent->category == 'verify')
                    $service->patientVerify($unsent->type_id);
                    $unsent->delete();

                    break;
                case 'appointment':
                   // $service->appointment();
                    break;

                default:
                    break;
            }
        }
    }
}
