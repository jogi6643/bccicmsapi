<?php

namespace App\Console\Commands; 

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\VideoContent;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
class Limelighturl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aws:jobstatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
     * @return mixed
     */
    public function handle()
    {
        $video_status = DB::connection('mongodb')->collection('videos')->where("video_status","processing")->where("video_url","!=", "")->select("ID","video_url","video_job_id")->get()->toArray();
        if(!empty($video_status)){
        foreach($video_status as $video_url){
            
       // print_r($file_extn);exit;
        $elasticTranscoder = ElasticTranscoderClient::factory(array(
            'credentials' => array(
                'key' => 'AKIAQ5OFAS6CZ2ANI2PQ',
                'secret' => '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',
            ),
            'region' => 'ap-south-1', // dont forget to set the region
            'version' => 'latest', // version string
        ));
        $jobStatus = $elasticTranscoder->readJob(array('Id' =>$video_url['video_job_id']));
       // print_r($jobStatus);exit;
        if($jobStatus['Job']['Status'] == "Complete"){
            $master_url = $video_url['video_url'];
            $folder = str_replace("_master.m3u8","",$master_url);
            $limelight_url = "https://epiconvod.s.llnwi.net/et/Converted_BCCI/Output/".$folder."/".$video_url['video_url'];
        VideoContent::where('ID', '=' ,$video_url['ID'])->update(['video_status' => "complete","video_url" =>$limelight_url]);
        }
    
    $this->info($video_url['ID']."limelighturl updated");
    

    }
}
}
}
