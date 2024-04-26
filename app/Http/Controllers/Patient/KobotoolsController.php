<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurveyResource;
use App\Jobs\KobotoolsJob;
use App\Models\Interview;
use App\Models\Patient;
use App\Services\InterviewService;
use App\Services\PatientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class KobotoolsController extends Controller
{


    public function jsondata()
    {
        ini_set('memory_limit', '-1');

        $path = storage_path("data.json");

        $json = json_decode(file_get_contents($path), true);


        foreach ($json['results'] as $result){
            KobotoolsJob::dispatch($result)
                ->delay(now()->addSeconds(5))
                ->onQueue('kobotools');
        }

        return new Response('Webhook Handled', 200);
    }
    public function index(InterviewService $service)
    {
        return SurveyResource::collection($service->getSubmissions());

    }
    public function store(Request $request)
    {


        KobotoolsJob::dispatch($request->all())->onQueue('kobotools');

        return new Response('Webhook Handled', 200);





    }

    public function show(int $id)
    {
        $interview = Interview::query()->with([
            'houseHold',
            'houseCharacteristics',
            'houseHoldCharacteristics',
            'houseHoldMembers',
            'sanitation',
            'housing',
            'waste',
            'calamity',
            'income'
        ])->findOrFail($id);

        return SurveyResource::make($interview);
    }

    public function logData(Request $request)
    {
        Log::alert('kobotools data', [
            'all' => $request->all()
        ]);

        return \response()->noContent();
    }
}
