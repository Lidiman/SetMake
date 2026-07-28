<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use RuntimeException;

class AvatarService
{
    private const AVATAR_SIZE = 256;
    private const QUALITY = 85;
    private const DIRECTORY = 'avatars';

    public function upload(UploadedFile $file, ?string $oldAvatar = null): ?string
    {
        try {
            $filename = $this->process($file);

            if ($oldAvatar && $this->exists($oldAvatar)) {
                Storage::disk('public')->delete($oldAvatar);
            }

            return $filename;
        } catch (\Exception $e) {
            Log::error('Avatar upload failed: '.$e->getMessage());
            throw new RuntimeException('Failed to upload avatar. Please try again.');
        }
    }

    private function process(UploadedFile $file): string
    {
        if (extension_loaded('gd')) {
            return $this->processWithImage($file);
        }

        return $this->processRaw($file);
    }

    private function processWithImage(UploadedFile $file): string
    {
        $manager = new ImageManager(['driver' => 'gd']);

        $image = $manager->read($file);
        $image->cover(self::AVATAR_SIZE, self::AVATAR_SIZE);

        $filename = self::DIRECTORY.'/'.str()->random(40).'.webp';
        $encoded = $image->encodeByExtension('webp', quality: self::QUALITY);

        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }

    private function processRaw(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $safe = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp'])
            ? strtolower($extension)
            : 'jpg';

        $filename = self::DIRECTORY.'/'.str()->random(40).'.'.$safe;

        Storage::disk('public')->put($filename, $file->get());

        return $filename;
    }

    public function delete(?string $path): bool
    {
        if (! $path || ! $this->exists($path)) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }

    public function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    public function exists(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        return Storage::disk('public')->exists($path);
    }

}
