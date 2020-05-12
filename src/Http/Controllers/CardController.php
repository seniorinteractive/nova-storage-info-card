<?php

namespace Qubeek\StorageInfoCard\Http\Controllers;

use Aws\ResultPaginator;
use Aws\S3\S3Client;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Cache;


class CardController extends Controller
{
    /**
     * @param int $size
     * @param int $items
     * @return \Illuminate\Http\JsonResponse
     */
    public function storage($size = 0, $items = 0)
    {
        $data = request()->validate([
            'disk' => 'required'
        ]);

        /** @var S3Client $client */
        list($s3, $bucket) = $this->getClient($data['disk']);

        /**
         * Check, that we working with S3 client only.
         * Otherwise we need to use different driver.
         * In future we should create extra functions to work with local drivers
         */
        if ($s3 instanceof S3Client) {
            /**
             * Fetch list of object from storage
             * @var ResultPaginator $results
             */
            $results = $s3->getPaginator('ListObjectsV2', [
                'Bucket' => $bucket,
            ]);

            /** Serve over results from request */
            foreach ($results as $result) {
                $size += array_sum(array_column($result['Contents'], 'Size'));
                $items += count(array_values($result['Contents']));
            }

            /**
             * Return success status and data for current disk.
             * Used choice to create interesting and common view for items
             */
            return Cache::remember('qubeek-nova-storage-info-card-'.$data['disk'], 5*60, function() use ($items, $bucket, $size) {
                return response()->json([
                    'status' => 200,
                    'size' => $this->bytesToHuman($size),
                    'bucket' => $bucket,
                    'items' => $this->prettyItems($items, 'объект|объекта|объектов')
                ], 200);
            });
        } else {
            /** Return error, when the disk doesn't provide S3 compatibility */
            return response()->json([
                'status' => 400,
                'message' => 'The disk doesn\'t provide S3 compatibility'
            ], 400);
        }
    }

    /**
     * Prettify output for bytes
     *
     * @param $bytes
     * @return string
     */
    protected function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Just prettify output
     *
     * @param int $items
     * @param string $values
     * @return string
     */
    protected function prettyItems(int $items, string $values)
    {
        return number_format($items, 0, ',', ' ') . ' ' . Lang::choice($values, $items, [], 'ru');
    }

    /**
     * Just prettify digging into object.
     * We can use it by one line, but this isn't clear
     *
     * @param string $disk
     * @return array
     */
    protected function getClient(string $disk)
    {
        /** @var FilesystemAdapter $fs */
        $fs = Storage::disk($disk);

        /** @var Filesystem $driver */
        $driver = $fs->getDriver();

        /** @var AwsS3Adapter $adapter */
        $adapter = $driver->getAdapter();

        return [
            $adapter->getClient(), $adapter->getBucket()
        ];
    }

}
