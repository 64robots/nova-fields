<?php

namespace R64\NovaFields\Http\Services;

use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RarArchive;
use SplFileInfo;
use ZipArchive;

class NormalizeFile
{
    use FileFunctions;

    /**
     * @var mixed
     */
    protected $storage;

    /**
     * @var mixed
     */
    protected $file;

    /**
     * @var mixed
     */
    protected $storagePath;

    /**
     * @param string $path
     */
    public function __construct(FilesystemAdapter $storage, string $path, string $storagePath)
    {
        $this->storage = $storage;
        $this->file = new SplFileInfo($path);

        $this->storagePath = $storagePath;
    }

    /**
     * @return mixed
     */
    public function toArray()
    {
        $data = collect([
            'name' => $this->file->getFilename(),
            'mime' => $this->getCorrectMimeFileType(),
            'path' => $this->storagePath,
            'size' => $this->getFileSize(),
            'url'  => $this->cleanSlashes($this->storage->url($this->storagePath)),
            'date' => $this->modificationDate(),
            'ext'  => $this->file->getExtension(),
        ]);

        $data = $this->setExtras($data);

        return $data->toArray();
    }

    /**
     * @param Collection $data
     * @return mixed
     */
    private function setExtras(Collection $data)
    {
        $mime = $this->storage->getMimetype($this->storagePath);

        // Image
        if (Str::contains($mime, 'image') || $data['ext'] == 'svg') {
            $data->put('type', 'image');
            $data->put('dimensions', $this->getDimensions($this->storage->getMimetype($this->storagePath)));
        }

        // Video
        if (Str::contains($mime, 'audio')) {
            $data->put('type', 'audio');
            $src = str_replace(env('APP_URL'), '', $this->storage->url($this->storagePath));
            $data->put('src', $src);
        }

        // Video
        if (Str::contains($mime, 'video')) {
            $data->put('type', 'video');
        }

        // text
        if ($this->availablesTextExtensions() && Str::contains($mime, 'text')) {
            $data->put('type', 'text');

            if ($data['size']) {
                $size = $this->file->getSize();
                if ($size < 350000) {
                    $data->put('source', $this->storage->get($this->storagePath));
                } else {
                    $data->put('source', __('Only files below 350 Kb will be shown'));
                }
            }
        }

        // text
        if (Str::contains($mime, 'pdf')) {
            $data->put('type', 'pdf');
        }

        // docx
        if (Str::contains($mime, 'wordprocessingml')) {
            $data->put('type', 'word');
            // $data->put('source', $this->storage->get($this->storagePath));
        }

        // zip
        if (Str::contains($mime, 'zip')) {
            $data->put('type', 'zip');
            $data->put('source', $this->readZip());
        }

        // // rar
        // if (Str::contains($mime, 'rar')) {
        //     $data->put('type', 'zip');
        //     $data->put('source', $this->readRar());
        // }

        $data->put('image', $this->getImage($mime, $data['ext']));

        return $data;
    }

    public function getFileSize()
    {
        try {
            return ($this->file->getSize() != 0) ? $this->formatBytes($this->file->getSize(), 0) : 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Returns the image or the svg icon preview.
     *
     * @return mixed
     */
    private function getImage($mime, $extension = false)
    {
        if (Str::contains($mime, 'image') || $extension == 'svg') {
            return $this->storage->url($this->storagePath);
        }

        $fileType = new FileTypesImages();

        return $fileType->getImage($mime);
    }

    /**
     * @param $mime
     */
    private function getDimensions($mime)
    {
        if (env('FILEMANAGER_DISK') != 'public') {
            return false;
        }

        if (Str::contains($mime, 'image')) {
            [$width, $height] = getimagesize($this->storage->path($this->storagePath));

            if (! empty($width) && ! empty($height)) {
                return $width.'x'.$height;
            }
        }

        return false;
    }

    /**
     * @param $timestamp
     */
    public function modificationDate()
    {
        try {
            return Carbon::createFromTimestamp($this->file->getMTime())->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return mixed
     */
    private function getCorrectMimeFileType()
    {
        $extension = $this->file->getExtension();
        $types = MimeTypes::checkMimeType($extension);

        if (count($types) > 0) {
            return reset($types);
        }

        //If no type

        return $this->storage->getMimetype($this->storagePath);
    }

    private function availablesTextExtensions()
    {
        $extension = $this->file->getExtension();
        $types = MimeTypes::checkMimeType($extension);

        $exist = false;
        for ($i = 0; $i < count($types); $i++) {
            if (Str::contains($types[$i], 'text') || Str::contains($types[$i], 'plain') || Str::contains($types[$i], 'sql') || Str::contains($types[$i], 'javascript')) {
                $exist = true;
                break;
            }
        }

        if ($exist) {
            return true;
        }

        return false;
    }

    /**
     * Read zip files.
     *
     * @return mixed
     */
    private function readZip()
    {
        $zip = new ZipArchive();
        $zip->open($this->storage->path($this->storagePath));
        $contents = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $contents[$stat['name']] = [
                'name' => $stat['name'],
                'size' => $stat['size'],
            ];
            // $contents[$stat['name']] = $stat['name'];
        }

        return $this->buildTree($contents);
    }

    /**
     * Read rar files.
     *
     * @return mixed
     */
    private function readRar()
    {
        $zip = new RarArchive();
        $zip->open($this->storage->path($this->storagePath));
        $contents = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $contents[$stat['name']] = [
                'name' => $stat['name'],
                'size' => $stat['size'],
            ];
            // $contents[$stat['name']] = $stat['name'];
        }

        return $this->buildTree($contents);
    }

    /**
     * @param $pathList
     * @return mixed
     */
    private function buildTree($pathList)
    {
        $data = [];
        foreach ($pathList as $path => $info) {
            $list = explode('/', trim($path, '/'));
            $last_dir = &$data;
            foreach ($list as $dir) {
                $last_dir = &$last_dir[$dir];
            }
            // $last_dir['info'] = $info;
        }
        $keys = $this->arrayKeysRecursive($data);
        array_reverse($keys);

        return json_encode($keys);
    }

    /**
     * @param array $array
     * @return mixed
     */
    private function arrayKeysRecursive(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $index[$key] = $this->arrayKeysRecursive($value);
            } else {
                $index[] = $key;
            }
        }

        return $index ?? [];
    }
}
