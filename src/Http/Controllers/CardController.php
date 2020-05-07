<?php

namespace Qubeek\StorageInfoCard\Http\Controllers;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function storage()
    {
        $sharedConfig = [
            'credentials' => [
                'key'      => env('YANDEX_KEY'),
                'secret'   => env('YANDEX_SECRET'),
            ],
            'region'   => env('YANDEX_REGION'),
            'endpoint' => 'https://'.env('YANDEX_STATIC_HOST'),
            'version'  => 'latest',
        ];

        $bucket = env('YANDEX_CONTAINER');

        // Instantiate the client.
        $s3 = new S3Client($sharedConfig);

        $objSize = 0;
        // Use the high-level iterators (returns ALL of your objects).
        try {
            $results = $s3->getPaginator('ListObjects', [
                'Bucket' => $bucket
            ]);

            foreach ($results as $result) {
                foreach ($result['Contents'] as $object) {
                    $objSize +=$object['Size'];
//                    echo $object['Key'] . PHP_EOL;
//                    echo $object['Size'] . PHP_EOL;
                }
            }
        } catch (S3Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        $objSize = $objSize / 1024;
        return response()->json($objSize, 200);
    }
}
