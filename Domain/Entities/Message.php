<?php
namespace Entities;
/** 
* @Entity(repositoryClass="Repositories\MessageRepository")
* @Table(name="message",indexes={@Index(name="text", columns={"text"})})
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

    /** @Column(name="text") */
    private $text;

    /** @Column(type="datetime", length=10) */
    private $date;

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
     * @param integer $messageId
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
     * @return integer
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
     * Set chatId
     *
     * @param \Chat $chatId
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
     * @return \Chat
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * Set userId
     *
     * @param \User $userId
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
     * @return \User
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
