<?php
namespace Chitanka\LibBundle\Service;

use \Swift_Mailer;
use \Swift_Message;
use Chitanka\LibBundle\Entity\Comment;
use Chitanka\LibBundle\Entity\WorkEntry;
use Chitanka\LibBundle\Entity\WorkContrib;
use Chitanka\LibBundle\Entity\User;

class Notifier {

	private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

	public function sendMailByNewWorkroomComment(Comment $comment, WorkEntry $workEntry, array $recipients)
	{
		if (empty($recipients)) {
			return;
		}
		$sender = array('NO_REPLY_I_REPEAT_NO_REPLY@chitanka.info' => $comment->getAuthorName().' (Моята библиотека)');
		$message = Swift_Message::newInstance('Коментар в ателието — '.$workEntry->getTitle())
			->setFrom($sender)
			->setBody($this->createMailBodyByNewWorkroomComment($comment, $workEntry));
		$headers = $message->getHeaders();
		$headers->addMailboxHeader('Reply-To', $sender);

		foreach ($recipients as $recipientEmail => $recipientName) {
			try {
				$message->setTo($recipientEmail, $recipientName);
			} catch (\Swift_RfcComplianceException $e) {
				$this->logError(__METHOD__.": {$e->getMessage()} (recipient: {$recipientName})");
			}
			$this->sendMessage($message);
		}
	}

	private function createMailBodyByNewWorkroomComment(Comment $comment, WorkEntry $workEntry)
	{
		return <<<BODY
{$comment->getBody()}
_______________________________________________________________________________
Автор на коментара: {$comment->getAuthorName()}
Относно: {$workEntry->getTitle()} ({$workEntry->getAuthor()})

Посетете работното ателие на Моята библиотека, за да отговорите на съобщението.
{$comment->getThread()->getPermalink()}#fos_comment_{$comment->getId()}

BODY;
	}

	public function sendMailByOldWorkEntry(WorkEntry $workEntry)
	{
		$sender = array('no-reply@chitanka.info' => 'Ателие (Моята библиотека)');
		$message = Swift_Message::newInstance('Стар запис — '.$workEntry->getTitle())
			->setFrom($sender)
			->setBody($this->createMailBodyByOldWorkEntry($workEntry));
		$headers = $message->getHeaders();
		$headers->addMailboxHeader('Reply-To', $sender);

		$recipient = $workEntry->getUser();
		try {
			$message->setTo($recipient->getEmail(), $recipient->getName());
		} catch (\Swift_RfcComplianceException $e) {
			$this->logError(__METHOD__.": {$e->getMessage()} (recipient: {$recipient->getName()})");
		}
		$this->sendMessage($message);

		foreach ($workEntry->getOpenContribs() as $contrib) {
			$this->sendMailByOldWorkContrib($contrib);
		}
	}

	public function sendMailByOldWorkContrib(WorkContrib $contrib)
	{
		$workEntry = $contrib->getEntry();
		$sender = array('no-reply@chitanka.info' => 'Ателие (Моята библиотека)');
		$message = Swift_Message::newInstance('Стар запис — '.$workEntry->getTitle())
			->setFrom($sender)
			->setBody($this->createMailBodyByOldWorkContrib($contrib));
		$headers = $message->getHeaders();
		$headers->addMailboxHeader('Reply-To', $sender);

		$recipient = $contrib->getUser();
		try {
			$message->setTo($recipient->getEmail(), $recipient->getName());
		} catch (\Swift_RfcComplianceException $e) {
			$this->logError(__METHOD__.": {$e->getMessage()} (recipient: {$recipient->getName()})");
		}
		$this->sendMessage($message);
	}

	private function createMailBodyByOldWorkEntry(WorkEntry $workEntry)
	{
		return <<<BODY
Здравейте!

В работното ателие на Моята библиотека има ваш запис, който не е бил обновяван от дълго време. Става дума за „{$workEntry->getTitle()}“ ({$workEntry->getAuthor()}).

http://chitanka.info/workroom/entry/{$workEntry->getId()}

Посетете ателието и отбележете текущото състояние на подготовката. В случай че нямате възможност да довършите обработката, качете готовото дотук и запишете, че е нужно някой друг да поеме работата.
_______________________________________________________________________________
Това е автоматично съобщение. Не отговаряйте на него!

BODY;
	}

	private function createMailBodyByOldWorkContrib(WorkContrib $workContrib)
	{
		$workEntry = $workContrib->getEntry();
		return <<<BODY
Здравейте!

В работното ателие на Моята библиотека има запис, към който сте се включили, който не е бил обновяван от дълго време. Става дума за „{$workEntry->getTitle()}“ ({$workEntry->getAuthor()}).

http://chitanka.info/workroom/entry/{$workEntry->getId()}

Посетете ателието и отбележете текущото състояние на подготовката в полето „Напредък“. В случай че нямате възможност да довършите обработката, качете готовото дотук и запишете, че е нужно някой друг да поеме работата.
_______________________________________________________________________________
Това е автоматично съобщение. Не отговаряйте на него!

BODY;
	}

	private function sendMessage(Swift_Message $message)
	{
		$this->mailer->send($message);
	}

	private function logError($message)
	{
		error_log($message);
	}
}
