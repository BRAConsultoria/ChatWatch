<?php
namespace Entities;
/** 
* @Entity(repositoryClass="Repositories\FileRepository")
* @Table(name="file",indexes={@Index(name="fileId", columns={"fileId"})})
*/
class File
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /** @Column(name="fileId", type="string", length=255) */
    private $fileId;

    /** @Column(type="string", length=100) */
    private $telegramType;

    /** @Column(type="string", length=100) */
    private $fileType;

    /** @Column(type="string", length=300) */
    private $fileName;

    /** @Column(type="string", length=300) */
    private $md5;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fileId
     *
     * @param string $fileId
     *
     * @return File
     */
    public function setFileId($fileId)
    {
        $this->fileId = $fileId;

        return $this;
    }

    /**
     * Get fileId
     *
     * @return string
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * Set telegramType
     *
     * @param string $telegramType
     *
     * @return File
     */
    public function setTelegramType($telegramType)
    {
        $this->telegramType = $telegramType;

        return $this;
    }

    /**
     * Get telegramType
     *
     * @return string
     */
    public function getTelegramType()
    {
        return $this->telegramType;
    }

    /**
     * Set fileType
     *
     * @param string $fileType
     *
     * @return File
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * Get fileType
     *
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return File
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set md5
     *
     * @param string $md5
     *
     * @return File
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;

        return $this;
    }

    /**
     * Get md5
     *
     * @return string
     */
    public function getMd5()
    {
        return $this->md5;
    }
}