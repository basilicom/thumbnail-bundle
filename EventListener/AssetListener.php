<?php
namespace Basilicom\ThumbnailBundle\EventListener;

use Basilicom\ThumbnailBundle\Message\ThumbnailJob;
use Pimcore\Event\Model\ElementEventInterface;
use Pimcore\Model\Asset;
use Pimcore\Model\Asset\Image\Thumbnail\Config as ThumbnailConfig;
use Symfony\Component\Messenger\MessageBusInterface;
use Pimcore\Logger;

class AssetListener
{

    private $bus;

    private $placeholderAssetFile;
    private $placeholderAsset = null; // unused for now

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
        $this->placeholderAssetFile = __DIR__ . '/../Resources/public/img/thumbnail.jpg';

        // @todo make file configurable, better use a "real" asset and generate
        // "real" thumbnails!
    }

    public function onPostUpdate(ElementEventInterface $e)
    {
        /** @var Asset $asset */
        $asset = $e->getAsset();

        Logger::debug('BTB AssetListener onPostUpdate for asset with ID ' . $asset->getId());

        if ($asset->getType() == 'folder') {
            return;
        }

        // system Thumbnail
        $systemPreviewConfig = ThumbnailConfig::getPreviewConfig();
        $this->createThumbnailPlaceholder($asset, $systemPreviewConfig);

        // thumbnail formats via CSV asset property "thumbnailConfig":
        $thumbnailConfigs = $asset->getProperty('thumbnailConfig');
        $thumbnailConfigList = explode(',', $thumbnailConfigs);
        foreach ($thumbnailConfigList as $thumbnailConfigName) {
            $thumbnailConfig = ThumbnailConfig::getByName($thumbnailConfigName);
            if (is_object($thumbnailConfig)) {
                $this->createThumbnailPlaceholder($asset, $thumbnailConfig);
            }
        }

        $this->bus->dispatch(new ThumbnailJob($asset->getId()));
        Logger::debug('BTB Dispatched ThumbnalJob for image with ID ' . $asset->getId());

    }

    public function onPostAdd(ElementEventInterface $e)
    {
        $this->onPostUpdate($e);
    }

    /**
     * @param $asset
     * @param $thumbnailConfig ThumbnailConfig
     * @todo use a real asset as thumbnail preview
     */
    private function createThumbnailPlaceholder($asset, $thumbnailConfig)
    {

        $path = $asset->getThumbnail($thumbnailConfig)->getFileSystemPath(true);
        if (!file_exists($path)) {
            $dir = dirname($path);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            copy($this->placeholderAssetFile, $path);
            @touch($path, $asset->getModificationDate());
            Logger::debug('BTB Created Placeholder '.$thumbnailConfig->getName()
                . ' for image with ID ' . $asset->getId()
                . ' Path: ' . $path);
        }
    }
}
