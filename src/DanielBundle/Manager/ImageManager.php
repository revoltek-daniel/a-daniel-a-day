<?php

namespace DanielBundle\Manager;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Knp\Component\Pager\Paginator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageManager
 *
 * @package DanielBundle\Manager
 */
class ImageManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var array
     */
    protected $repository;

    /**
     * @var  string
     */
    protected $class;

    /**
     * @var array
     */
    protected $allowedFiletypes = array('image/jpeg', 'image/png');

    /**
     * @param EntityManager $entityManager
     * @param Paginator     $paginator
     * @param string        $class
     * @param string        $filepath
     */
    public function __construct(EntityManager $entityManager, Paginator $paginator, $class, $filepath)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->class = $class;
        $this->filepath = $filepath;
    }

    /**
     * get paginated entry list.
     *
     * @param int $page
     * @param int $limit
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getPaginatedList($page = 1, $limit = 10)
    {
        $dql   = "SELECT g FROM DanielBundle:Image g";
        $query = $this->entityManager->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $limit
        );

        return $pagination;
    }

    /**
     * Upload file.
     *
     * @param UploadedFile $file
     * @param string       $imageId
     *
     * @return string
     * @throws \Exception
     */
    public function uploadNewPicture(UploadedFile $file, $imageId)
    {
        if (!in_array($file->getMimeType(), $this->allowedFiletypes)) {
            throw new \Exception('tubemesh.user.edit.picture.invalid');
        }

        $filepath = $this->filepath;
        $filename = sha1(mt_rand(0, 50) . $imageId . mt_rand(0, 50) . $file->getClientOriginalName() . mt_rand(0, 50)) . '.' . $file->getClientOriginalExtension();

        $file->move($filepath, $filename);

        $this->resizePictures($filepath, $filename);

        return $filename;
    }

    /**
     * Remove Old File.
     *
     * @param $oldFile
     *
     * @return void
     */
    public function removeOldPicture($oldFile)
    {
        $file = $this->filepath . DIRECTORY_SEPARATOR . $oldFile;
        if ($oldFile && file_exists($file)) {
            unlink($file);
            $thumb = $this->filepath . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . $oldFile;
            if (file_exists($thumb)) {
                unlink($thumb);
            }
        }
    }

     /**
     * Resize picture and save thumb.
     *
     * @param string $filepath
     * @param string $filename
     *
     * @return void
     */
    protected function resizePictures($filepath, $filename)
    {
        $fullpath = $filepath . DIRECTORY_SEPARATOR . $filename;

        $thumbPath = $filepath . DIRECTORY_SEPARATOR . 'thumb';
        if (!is_dir($thumbPath)) {
            if (!mkdir($thumbPath) && !is_dir($thumbPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $thumbPath));
            }
        }

        $imagine = new Imagine();

        $image = $imagine->open($fullpath);
        $image->resize(new Box(120, 120))
        ->save($thumbPath . DIRECTORY_SEPARATOR . $filename);

        $image = $imagine->open($fullpath);
        $image->resize(new Box(900, 600))
        ->save($fullpath);
    }

    /**
     *
     *
     * @return mixed
     */
    public function findAllByReverseOrder()
    {
        $dql = 'SELECT g FROM DanielBundle:Image g ORDER BY g.id DESC';
        $query = $this->entityManager->createQuery($dql);

        return $query->execute();
    }

    /**
     *
     *
     * @return mixed
     */
    public function getLastEntry()
    {
        $dql   = 'SELECT g FROM DanielBundle:Image g ORDER BY g.id DESC';
        $query = $this->entityManager->createQuery($dql)->setMaxResults(1);

        return $query->execute();
    }

    public function getNextEntry(int $id)
    {
        $dql   = 'SELECT g FROM DanielBundle:Image g WHERE g.id > :id ORDER BY g.id ASC';
        $query = $this->entityManager->createQuery($dql)->setMaxResults(1)->setParameter('id', $id);

        return $query->execute();
    }

    public function getBeforeEntry(int $id)
    {
        $dql   = 'SELECT g FROM DanielBundle:Image g WHERE g.id < :id ORDER BY g.id DESC';
        $query = $this->entityManager->createQuery($dql)->setMaxResults(1)->setParameter('id', $id);

        return $query->execute();
    }
}
