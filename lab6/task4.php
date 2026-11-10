<?php

class TwitterService 
{
    protected array $_data = [];

    public function setMessage(string $text): void 
    {
        $this->_data['message'] = $text;
    }

    public function setScheduleTime(string $time): void 
    {
        $this->_data['time'] = $time;
    }

    public function sendTweet(): void 
    {
        $timeInfo = $this->_data['time'] ?? 'зараз';
        echo "[Twitter] Повідомлення: '{$this->_data['message']}' | Час відправлення: {$timeInfo}<br>\n";
    }
}

class SmsService 
{
    private string $recipient = '';
    private string $message = '';
    private string $sendTime = '';

    public function setRecipient(string $recipient): void 
    {
        $this->recipient = $recipient;
    }

    public function setMessage(string $message): void 
    {
        $this->message = $message;
    }

    public function setDeliveryTime(string $time): void 
    {
        $this->sendTime = $time;
    }

    public function sendText(): void 
    {
        $timeInfo = $this->sendTime ?: 'негайно';
        echo "[SMS] Отримувач: {$this->recipient} | Текст: '{$this->message}' | Час відправлення: {$timeInfo}<br>\n";
    }
}

class EmailService 
{
    private string $email = '';
    private string $body = '';
    private string $sendAt = '';

    public function setAddress(string $email): void { $this->email = $email; }
    public function setBody(string $body): void { $this->body = $body; }
    public function setSendAt(string $time): void { $this->sendAt = $time; }

    public function sendMail(): void 
    {
        echo "[Email] Адреса: {$this->email} | Лист: '{$this->body}' | Заплановано на: {$this->sendAt}<br>\n";
    }
}

interface NotificationInterface 
{
    public function setData(array $data);
    public function setSendTime(string $time);
    public function sendNotification();
}

class TwitterAdapter implements NotificationInterface 
{
    protected array $_data = [];
    protected string $_sendTime = '';

    public function setData(array $data): void 
    {
        $this->_data = $data;
    }

    public function setSendTime(string $time): void 
    {
        $this->_sendTime = $time;
    }

    public function sendNotification(): void 
    {
        $twitterClient = new TwitterService();
        $twitterClient->setMessage($this->_data['message']);
        $twitterClient->setScheduleTime($this->_sendTime);
        $twitterClient->sendTweet();
    }
}

class SmsAdapter implements NotificationInterface 
{
    protected array $_data = [];
    protected string $_sendTime = '';

    public function setData(array $data): void 
    {
        $this->_data = $data;
    }

    public function setSendTime(string $time): void 
    {
        $this->_sendTime = $time;
    }

    public function sendNotification(): void 
    {
        $smsClient = new SmsService();
        $smsClient->setRecipient($this->_data['recipient']);
        $smsClient->setMessage($this->_data['message']);
        $smsClient->setDeliveryTime($this->_sendTime);
        $smsClient->sendText();
    }
}

class EmailAdapter implements NotificationInterface 
{
    protected array $_data = [];
    protected string $_sendTime = '';

    public function setData(array $data): void { $this->_data = $data; }
    public function setSendTime(string $time): void { $this->_sendTime = $time; }

    public function sendNotification(): void 
    {
        $emailClient = new EmailService();
        $emailClient->setAddress($this->_data['recipient']);
        $emailClient->setBody($this->_data['message']);
        $emailClient->setSendAt($this->_sendTime);
        $emailClient->sendMail();
    }
}

interface INotificationManager 
{
    public function sendNotification(string $type, array $data, string $sendTime = '');
}

class NotificationManager implements INotificationManager 
{
    public function sendNotification(string $type = '', array $data = [], string $sendTime = '') 
    {
        switch ($type) {
            case "twitter":
                $notification = new TwitterAdapter();
                break;
            case "sms":
                $notification = new SmsAdapter();
                break;
            case "email":
                $notification = new EmailAdapter();
                break;
            default:
                echo "Помилка: невідомий тип повідомлення<br>\n";
                return false;
        }

        $time = $sendTime ?: ($data['time'] ?? date('Y-m-d H:i:s'));

        $notification->setData($data);
        $notification->setSendTime($time);
        $notification->sendNotification();
    }
}

$manager = new NotificationManager();

$tweetData = ["message" => "This is tweet"];
$manager->sendNotification("twitter", $tweetData, "2026-05-15 10:00:00");

$smsData = [
    "recipient" => "+380501234567",
    "message"   => "Ваше замовлення готове до видачі!"
];
$manager->sendNotification("sms", $smsData, "2026-05-15 14:30:00");

$emailData = [
    "recipient" => "student@sumdu.edu.ua",
    "message"   => "Лабораторну роботу №6 успішно перевірено."
];
$manager->sendNotification("email", $emailData, "2026-05-15 16:00:00");

?>