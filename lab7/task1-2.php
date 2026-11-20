<?php

abstract class PaymentAccount
{
    protected ?PaymentAccount $nextAccount = null;
    protected float $balance;
    protected string $accountName;

    public function __construct(string $accountName, float $balance)
    {
        $this->accountName = $accountName;
        $this->balance = $balance;
    }

    public function setNext(PaymentAccount $account): PaymentAccount
    {
        $this->nextAccount = $account;
        return $account;
    }

    public function pay(float $amount): void
    {
        if ($this->canPay($amount)) {
            $this->balance -= $amount;
            echo "Оплату на суму {$amount} грн успішно виконано з рахунку: <b>{$this->accountName}</b>. "
               . "Залишок: {$this->balance} грн.<br>";
        } elseif ($this->nextAccount !== null) {
            echo "Недостатньо коштів на рахунку «{$this->accountName}» (доступно: {$this->balance} грн). "
               . "Перевірка наступного рахунку...<br>";
            $this->nextAccount->pay($amount);
        } else {
            echo "<b>Оплату на суму {$amount} грн відхилено:</b> недостатньо коштів на всіх доступних рахунках.<br>";
        }
    }

    protected function canPay(float $amount): bool
    {
        return $this->balance >= $amount;
    }
}

class MainAccount extends PaymentAccount
{
    public function __construct(float $balance)
    {
        parent::__construct("Основний рахунок", $balance);
    }
}

class CreditCard extends PaymentAccount
{
    public function __construct(float $balance)
    {
        parent::__construct("Кредитна картка", $balance);
    }
}

$mainAccount = new MainAccount(500);
$creditCard = new CreditCard(2000);
$mainAccount->setNext($creditCard);

echo "<h3>Спроба 1: Покупка на 350 грн</h3>";
$mainAccount->pay(350);

echo "<h3>Спроба 2: Покупка на 1200 грн</h3>";
$mainAccount->pay(1200);

echo "<h3>Спроба 3: Покупка на 5000 грн</h3>";
$mainAccount->pay(5000);
?>