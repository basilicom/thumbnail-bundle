<?php
// src/Message/SmsNotification.php

namespace Basilicom\ThumbnailBundle\Message;

class ThumbnailJob
{
    private $assetId;

    public function __construct($assetId)
    {
        $this->assetId = $assetId;
    }

    public function getAssetId()
    {
        return $this->assetId;
    }
}