<?php

namespace Ai\GalleryBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Oneup\UploaderBundle\Uploader\Orphanage\OrphanageManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageManager
{
    //Must be be equal oneup_uploader.mappings.*
    const TYPE_ALBUM='album';
    const TYPE_IMAGE='image';

    static $IMAGE_PATHS = [
        self::TYPE_ALBUM => 'uploads/album',
        self::TYPE_IMAGE => 'uploads/image',
    ];

    static $THUMB_FILTERS= [
        self::TYPE_ALBUM => 'albums_thumb',
        self::TYPE_IMAGE => 'images_thumb',
    ];

    protected $imageCacheManager;
    protected $imageDataManager;
    protected $imageFilterManager;
    protected $orphanageManager;
    protected $rootDir;

    /**
     * @param CacheManager $imageCacheManager
     * @param DataManager $imageDataManager
     * @param FilterManager $imageFilterManager
     * @param OrphanageManager $orphanageManager
     * @param string $rootDir
     */
    public function __construct(
        CacheManager $imageCacheManager,
        DataManager $imageDataManager,
        FilterManager $imageFilterManager,
        OrphanageManager $orphanageManager,
        Filesystem $fs,
        $rootDir
    )
    {
        $this->imageCacheManager = $imageCacheManager;
        $this->imageDataManager = $imageDataManager;
        $this->imageFilterManager = $imageFilterManager;
        $this->orphanageManager = $orphanageManager;
        $this->fs = $fs;
        $this->rootDir = $rootDir;
    }

    /**
     * Removes file by type and filename
     *
     * @param $type
     * @param $filename
     * @return bool
     */
    public function removeFile($type, $filename)
    {
        try {
            $filePath = $this->getImagePath($type, $filename);
            unlink($filePath);
        } catch(NotFoundHttpException $e) {
            return false;
        }

        return true;
    }

    /**
     * Uploads orphanage image to right directory
     *
     * @param $type
     * @param $filename
     */
    public function orphanageUploads($type, $filename)
    {
        $manager = $this->orphanageManager->get($type);
        $files = $manager->getFiles();

        // reduce the scope of the Finder object to what you want
        $files->files()->name($filename);
        $manager->uploadFiles(iterator_to_array($files));
    }

    /**
     * Gets images data for FineUploader session.endpoint
     *
     * @param array $imgReq = [['type' => 'album', 'filename' => 'finename.png']]
     * @return array
     */
    public function getImagesData(array $imgReq)
    {
        $data=[];
        foreach($imgReq as $req)
        {
            try {
                $data[] = $this->getImageData($req['type'], $req['filename']);
            } catch(NotFoundHttpException $e) {
                //
            }
        }

        return $data;
    }

    /**
     * Gets image data for FineUploader session.endpoint
     *
     * @param $type
     * @param $fileName
     * @return array
     */
    public function getImageData($type, $fileName)
    {
        $imagePath = $this->getImagePath($type, $fileName);
        $image = new File($imagePath);
        $thumb = $this->getImageThumb($type, $fileName);

        return [
            'uuid'               => $image->getBasename(),
            'name'               => $fileName,
            'size'               => $image->getSize(),
            'thumbnailUrl'       => $thumb,
        ];
    }

    /**
     * @param $type
     * @param $fileName
     *
     * @return string Image Src
     * @throws \Exception
     */
    public function getImageThumb($type, $fileName)
    {
        $imagePath = $this->getImagePath($type, $fileName);
        return $this->imageFilter($imagePath, self::$THUMB_FILTERS[$type]);
    }

    /**
     * @param $type
     * @param $fileName
     * @return string
     */
    public function getImagePath($type, $fileName)
    {
        if(
            !array_key_exists($type, self::$IMAGE_PATHS) ||
            !array_key_exists($type, self::$THUMB_FILTERS)
        ){
            throw new NotFoundHttpException('Image type not found');
        }

        $imagePath = self::$IMAGE_PATHS[$type].'/'.$fileName;

        if(!$this->fs->exists($imagePath)){
            throw new NotFoundHttpException('Image not found!');
        }

        return $imagePath;
    }

    /**
     * @param $path
     * @param $filter
     * @return string Image Src
     */
    protected function imageFilter($path, $filter)
    {
        if (!$this->imageCacheManager->isStored($path, $filter)) {
            $binary = $this->imageDataManager->find($filter, $path);

            $filteredBinary = $this->imageFilterManager->applyFilter($binary, $filter);

            $this->imageCacheManager->store($filteredBinary, $path, $filter);
        }

        return $this->imageCacheManager->resolve($path, $filter);
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getAbsoluteUploadPath($type)
    {
        $uploadPath = self::$IMAGE_PATHS[$type];
        return $this->rootDir . '/../web/' . $uploadPath;
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getAbsoluteThumbPath($type)
    {
        $uploadPath = self::$IMAGE_THUMB_PATHS[$type];
        return $this->rootDir . '/../web/' . $uploadPath . '/thumbs';
    }
}