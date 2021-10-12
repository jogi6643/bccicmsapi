<?php

namespace App\Console\Commands; 

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\VideoContent;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
class AwsElasticTranscoderHls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aws:elastichls';

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
        $video_status = DB::connection('mongodb')->collection('videos')->where("video_status","pending")->where("video_url","!=", "")->select("ID","video_url")->get()->toArray();
        if(!empty($video_status)){
        foreach($video_status as $video_url){
            
            $input = str_replace("https://mum-epicon-bcci.s3.ap-south-1.amazonaws.com/","",$video_url['video_url']);
            
        $file_name = explode('/',$input);
       // print_r($file_name);exit;
	    $file_extn = explode('.',end($file_name));
       // print_r($file_extn);exit;
        $elasticTranscoder = ElasticTranscoderClient::factory(array(
            'credentials' => array(
                'key' => 'AKIAQ5OFAS6CZ2ANI2PQ',
                'secret' => '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',
            ),
            'region' => 'ap-south-1', // dont forget to set the region
            'version' => 'latest', // version string
        ));
        // HLS Presets that will be used to create an adaptive bitrate playlist.
// HLS Presets that will be used to create an adaptive bitrate playlist.
$hls_64k_audio_preset_id = '1351620000001-200071';
$hls_0400k_preset_id     = '1351620000001-200050';
$hls_0600k_preset_id     = '1351620000001-200040';
$hls_1000k_preset_id     = '1351620000001-200030';
$hls_1500k_preset_id     = '1351620000001-200020';
$hls_2000k_preset_id     = '1351620000001-200010';

$hls_presets = [
  'hlsAudio' => $hls_64k_audio_preset_id,
  'hls0400k' => $hls_0400k_preset_id,
  'hls0600k' => $hls_0600k_preset_id,
  'hls1000k' => $hls_1000k_preset_id,
  'hls1500k' => $hls_1500k_preset_id,
  'hls2000k' => $hls_2000k_preset_id,
];

// HLS Segment duration that will be targeted.
$segment_duration = '10';

$outputs = [];
foreach ($hls_presets as $prefix => $preset_id) {
    array_push( $outputs, [
        'Key' => $prefix . '_' . $file_extn[0], 
        'PresetId' => $preset_id, 
        'SegmentDuration' => $segment_duration,
    ]);
  };

  // All outputs will have this prefix prepended to their output key.
$output_key_prefix = 'Converted_BCCI/Output/'.$file_extn[0].'/';

$playlist = [ 
    'Name' => $file_extn[0].'_master',
    'Format' => 'HLSv3',
    'OutputKeys' => array_map(function($x) { return $x['Key']; }, $outputs),
    'HlsContentProtection' => array(
        "Method" => "aes-128",
       "LicenseAcquisitionUrl"=>"http://52.66.77.143/get-secure-key",
       //"LicenseAcquisitionUrl"=>"https://dev.epicon.in/img", 
        "KeyStoragePolicy" => "NoStore", //"NoStore|WithVariantPlaylists"
        "Key"=>"AQIDAHg/4Gvf6puDEaq3pajC6BcrwiaES5y5+L57QWRCwjFNTgFJXFV5XjyqA11OsZQ/+af0AAAAbjBsBgkqhkiG9w0BBwagXzBdAgEAMFgGCSqGSIb3DQEHATAeBglghkgBZQMEAS4wEQQMFgoeTty6yRJsgV9mAgEQgCuZ4o9q2+DX+83IXPeRJycyv9Bu6mreDSDILJ2y/3isBymTVM7M5IpOBgAE",
        "KeyMd5" => "+sp1lATKMXTNuy9nLxx87g==",
        "InitializationVector" =>"IZcg3vmqPn3k2f4E2IyA5w=="
    )

  ];

      // Create the job.
try {
    $create_job_result = $elasticTranscoder->createJob([
        'PipelineId' => '1626784053777-9vk8pt',
        'Input' => ['Key' => $input],
        'Outputs' => $outputs,
        'OutputKeyPrefix' => $output_key_prefix,
        'Playlists' => [ $playlist ],
    ]);
    $master_file = $create_job_result['Job']['Playlists'][0]['Name'].".m3u8";
    VideoContent::where('ID', '=' ,$video_url['ID'])->update(['video_job_id' => $create_job_result['Job']['Id'],'video_status' => "processing","video_url" =>$master_file]);
     //$create_job_result['Job']['Id'];
    $this->info($create_job_result['Job']['Id']."JobID created for video");
    //  $jobStatus = $elasticTranscoder->readJob(array('Id' => $create_job_result["Job"]['Id']));
    //  print_r($jobStatus['Job']['Playlists'][0]['Name']);exit;
} catch (AwsException $e) {
    // output error message if fails
    return $e->getMessage() . "\n";
}

    }
}
}
}
