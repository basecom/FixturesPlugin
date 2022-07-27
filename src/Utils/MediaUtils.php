<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Content\Media\File\FileFetcher;
use Shopware\Core\Content\Media\File\FileSaver;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;

class MediaUtils
{
    private EntityRepositoryInterface $mediaRepository;
    private FileSaver $fileSaver;
    private FileFetcher $fileFetcher;

    public function __construct(EntityRepositoryInterface $mediaRepository, FileSaver $fileSaver, FileFetcher $fileFetcher)
    {
        $this->mediaRepository = $mediaRepository;
        $this->fileSaver       = $fileSaver;
        $this->fileFetcher     = $fileFetcher;
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
