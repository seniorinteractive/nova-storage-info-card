<?php

namespace Qubeek\StorageInfoCard;

use Laravel\Nova\Card;

class StorageInfoCard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'storage-info-card';
    }

    /**
     * @param bool $name
     * @return StorageInfoCard
     * Name of your storage
     */
    public function name($name = true)
    {
        return $this->withMeta(['name' => $name]);
    }

    /**
     * @param bool $count
     * @param bool $measure
     * @return StorageInfoCard
     * Maximum amount of available memory in storage
     */
    public function memory($count = true, $measure = true)
    {
        return $this->withMeta([
            'count' => $count,
            'measure' => $measure]);
    }

    /**
     * @param bool $disk_name
     * @return StorageInfoCard
     * Name of file system provider
     */
    public function disk($disk_name = true)
    {
        return $this->withMeta([
            'disk_name' => $disk_name
        ]);
    }
}
