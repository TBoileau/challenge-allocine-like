<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

trait UploadImageTrait
{
    protected function createImage(): UploadedFile
    {
        $filename = sprintf('%s.png', (string) Uuid::v4());
        $filePath = sprintf('%s/../public/uploads/%s', __DIR__, $filename);
        copy(sprintf('%s/../public/uploads/image.png', __DIR__), $filePath);

        return new UploadedFile($filePath, $filename, null, null, true);
    }
}
