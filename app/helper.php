<?php
use Elasticsearch\ClientBuilder;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
if (!function_exists('APIResponse')) {
    function APIResponse ($statusCode = '', $message = '', $data = '') {
        $response = array();
        $response['status'] = [
            'code' => $statusCode,
            'message' => $message
        ];

        if (!empty($data) && !is_array($data))
            $response['payload'] = (object) $data->toArray();
        elseif (!empty($data))
            $response['payload'] = (object) $data;

        $response = response()->json($response, $statusCode);

        return $response;
    }
}

if (!function_exists('uploadFileToS3')) {
    function uploadFileToS3 ($file, $folderPath, $tag = '') {
        $s3 = new Aws\S3\S3Client([
            'region'  => env('AWS_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_KEY'),
                'secret' => env('AWS_SECRET'),
            ]
        ]);

        $name = time() . '_' . $file->getClientOriginalName();
        if ($tag == 'video') {
            $result = $s3->putObject([
                'Bucket' => 'mum-epicon-bcci',
                'Key'    => $folderPath . $name,
                'SourceFile' => $file->getRealPath()
            ]);
        } else {
           $result = $s3->putObject([
                'Bucket' => 'bcciplayerimages',
                'Key'    => $folderPath . $name,
                'SourceFile' => $file->getRealPath()
            ]); 
        }
        
        return $result;
    }
}

if (!function_exists('getElasticHosts')) {
    function getElasticHosts () {
        $host = env('ELASTICSEARCH_SCHEME') . '://' . env('ELASTICSEARCH_HOST') . ':' . env('ELASTICSEARCH_PORT');
        
		$hosts[] = $host;

        return $hosts;
    }
}

if (!function_exists('elasticCreate')) {
    function elasticCreate ($index, $id, $body) {
        $params = [
			'index' => $index,
			'id'    => $id,
			'body'  => $body
		];
        
		$hosts = getElasticHosts();
		$client = ClientBuilder::create()->setHosts($hosts)->build();
		$response = $client->index($params);

        return $response;
    }
}

if (!function_exists('elasticGetDocument')) {
    function elasticGetDocument ($index, $id) {
        $params = [
			'index' => $index,
            'id' => $id
		];
        
		$hosts = getElasticHosts();
		$client = ClientBuilder::create()->setHosts($hosts)->build();

        $response = $client->get($params);
        return $response['_source'];
    }
}

if (!function_exists('elasticUpdate')) {
    function elasticUpdate ($index, $id, $body) {
        $params = [
			'index' => $index,
			'id'    => $id,
			'body'  => $body
		];

        $hosts = getElasticHosts();
		$client = ClientBuilder::create()->setHosts($hosts)->build();
		$response = $client->update($params);

        return $response;
    }
}

if (!function_exists('elasticDelete')) {
    function elasticDelete ($index, $id) {
        $params = [
			'index' => $index,
			'id'    => $id
		];

        $hosts = getElasticHosts();
		$client = ClientBuilder::create()->setHosts($hosts)->build();
		$response = $client->delete($params);

        return $response;
    }
}

if (!function_exists('elasticSearch')) {
    function elasticSearch ($index, $body) {
        $params = [
			'index' => $index,
			'body'  => $body
		];
        
        $hosts = getElasticHosts();
		$client = ClientBuilder::create()->setHosts($hosts)->build();
		$response = $client->search($params);

        return $response;
    }
}

if (!function_exists('search')) {
    function search ($type, $searchTerm) {
        $query = array(
            'query' => array(
                'bool' => array(
                    'must' => array(
                        'term' => array(
                            'title' => $searchTerm
                        )
                    ),
                    'filter' => array(
                        'term' => array(
                            'status' => true
                        )
                    )
                )
            ),
            'sort' => array(
                'ID' => 'desc'
            )
        );

        $data = elasticSearch($type, $query);

        return $data;
    }
}

if (!function_exists('Aws_elastic_transcoder')) {
    function Aws_elastic_transcoder ($file_name,$file_obj) {
        $file_name = explode('/','Input/PRDPTRLSUPOVERSEASSTEREO25FPSconverted.mp4');
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
         'Input' => ['Key' => 'Input/PRDPTRLSUPOVERSEASSTEREO25FPSconverted.mp4'],
         'Outputs' => $outputs,
         'OutputKeyPrefix' => $output_key_prefix,
         'Playlists' => [ $playlist ],
     ]);
     //var_dump($create_job_result["Job"]['Id']);
     return $create_job_result['Job']['Id'];
     // $jobStatus = $elasticTranscoder->readJob(array('Id' => $create_job_result["Job"]['Id']));
     // print_r($jobStatus['Job']['Status']);exit;
 } catch (AwsException $e) {
     // output error message if fails
     return $e->getMessage() . "\n";
 }
 
     }
}



