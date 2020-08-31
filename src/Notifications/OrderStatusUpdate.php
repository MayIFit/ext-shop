<?php

namespace MayIFit\Extension\Shop\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use MayIFit\Core\Permission\Models\SystemSetting;

/**
 * Class OrderStatusUpdate
 *
 * @package MayIFit\Extension\Shop
 */
class OrderStatusUpdate extends Notification implements ShouldQueue
{
    use Queueable;

    protected $senderEmail;
    protected $senderName;
    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
        $this->senderEmail = (SystemSetting::where('setting_name', 'shop.emailFrom')->first())->setting_value;
        $this->senderName = (SystemSetting::where('setting_name', 'shop.emailFromName')->first())->setting_value;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $locale = $this->order->reseller->user->language;
        $url = url(rtrim(config('app.url')) . '/order?token=' . $this->order->token);
        return (new MailMessage)
            ->from($this->senderEmail, $this->senderName)
            ->greeting(trans('global.hello', [], $locale))
            ->line(trans('action.your_order_has_changed', [], $locale) . ": " . trans('order.' . $this->order->orderStatus->name, [], $locale))
            ->action(trans('action.check_here', [], $locale), $url)
            ->line(trans('global.thank_you_for_ordering', [], $locale))
            ->salutation(trans('global.regards', [], $locale) . " " . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
