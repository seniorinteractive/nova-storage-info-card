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
     * Maximum amount of available memory in storage.
     *
     * @param string $size
     *
     * @return StorageInfoCard
     */
    public function memory(string $size)
    {
        return $this->withMeta([
            'size' => $size,
        ]);
    }

    /**
     * Add disks to work with them.
     *
     * @param array $disks
     *
     * @return StorageInfoCard
     */
    public function disks(array $disks)
    {
        return $this->withMeta([
            'disks' => $disks,
        ]);
    }
}
