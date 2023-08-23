<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Content\Media\Aggregate\MediaFolder\MediaFolderEntity;
use Shopware\Core\Content\Media\File\FileFetcher;
use Shopware\Core\Content\Media\File\FileSaver;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class MediaUtils
{
    /**
     * @TODO Replace with real typehint when Shopware 6.4 support is dropped.
     *
     * @var EntityRepository
     */
    private $mediaRepository;

    /**
     * @TODO Replace with real typehint when Shopware 6.4 support is dropped.
     *
     * @var EntityRepository
     */
    private $mediaFolderRepository;
    private FileSaver $fileSaver;
    private FileFetcher $fileFetcher;

    /**
     * @TODO Replace with real typehints when Shopware 6.4 support is dropped.
     *
     * @param EntityRepository $mediaRepository
     * @param EntityRepository $mediaFolderRepository
     *
     * @noinspection PhpMissingParamTypeInspection We can not use typehints until repository decorators are removed (Shopware 6.5).
     */
    public function __construct($mediaRepository, $mediaFolderRepository, FileSaver $fileSaver, FileFetcher $fileFetcher)
    {
        $this->mediaRepository       = $mediaRepository;
        $this->mediaFolderRepository = $mediaFolderRepository;
        $this->fileSaver             = $fileSaver;
        $this->fileFetcher           = $fileFetcher;
    }

    /**
     * Copied from "vendor/shopware/core/Content/Media/MediaService.php".
     */
    public function getDefaultFolder(string $folderName): ?MediaFolderEntity
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('media_folder.defaultFolder.entity', $folderName))
            ->addAssociation('defaultFolder')
            ->setLimit(1);

        $mediaFolder = $this->mediaFolderRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();

        return $mediaFolder instanceof MediaFolderEntity ? $mediaFolder : null;
    }

    public function upload(string $mediaId, string $folderId, string $filename, string $extension, string $contentType): void
    {
        $ctx = Context::createDefaultContext();

        $this->mediaRepository->upsert([
            [
                'id'            => $mediaId,
                'mediaFolderId' => $folderId,
            ],
        ],
            $ctx
        );

        $uploadedFile = $this->fileFetcher->fetchBlob(
            (string) file_get_contents($filename),
            $extension,
            $contentType
        );

        $this->fileSaver->persistFileToMedia(
            $uploadedFile,
            basename($filename, '.'.$extension),
            $mediaId,
            $ctx
        );
    }
}
