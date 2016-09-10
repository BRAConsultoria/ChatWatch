<?php
namespace Entities;
/** 
* @Entity(repositoryClass="Repositories\MessageRepository")
* @Table(name="message",indexes={@Index(columns={"text"}, flags={"fulltext"})})
*/
class Message
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /** @Column(name="messageId", type="string", length=50) */
    private $messageId;

    /**
    * @ManyToOne(targetEntity="Chat", inversedBy="chatId")
    * @JoinColumn(name="chatId", referencedColumnName="id")
    */
    private $chatId;

    /**
    * @ManyToOne(targetEntity="User", inversedBy="userId")
    * @JoinColumn(name="userId", referencedColumnName="id")
    */
    private $userId;

    /**
    * @ManyToOne(targetEntity="File", inversedBy="fileId")
    * @JoinColumn(name="fileId", referencedColumnName="id", nullable=true)
    */
    private $fileId;

    /** @Column(type="text") */
    private $text;

    /** @Column(type="datetime", length=10) */
    private $date;

    /** @Column(type="boolean", nullable=TRUE, options={"default": 0}) */
    private $pinned;


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
     * Set messageId
     *
     * @param string $messageId
     *
     * @return Message
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * Get messageId
     *
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Message
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set pinned
     *
     * @param boolean $pinned
     *
     * @return Message
     */
    public function setPinned($pinned)
    {
        $this->pinned = $pinned;

        return $this;
    }

    /**
     * Get pinned
     *
     * @return boolean
     */
    public function getPinned()
    {
        return $this->pinned;
    }

    /**
     * Set chatId
     *
     * @param \Entities\Chat $chatId
     *
     * @return Message
     */
    public function setChatId(\Entities\Chat $chatId = null)
    {
        $this->chatId = $chatId;

        return $this;
    }

    /**
     * Get chatId
     *
     * @return \Entities\Chat
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * Set userId
     *
     * @param \Entities\User $userId
     *
     * @return Message
     */
    public function setUserId(\Entities\User $userId = null)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return \Entities\User
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set fileId
     *
     * @param \Entities\File $fileId
     *
     * @return Message
     */
    public function setFileId(\Entities\File $fileId = null)
    {
        $this->fileId = $fileId;

        return $this;
    }

    /**
     * Get fileId
     *
     * @return \Entities\File
     */
    public function getFileId()
    {
        return $this->fileId;
    }
}