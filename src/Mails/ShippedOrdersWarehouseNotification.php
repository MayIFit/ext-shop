<?php

namespace MayIFit\Extension\Shop\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class ShippedOrdersWarehouseNotification
 *
 * @package MayIFit\Extension\Shop
 */
class ShippedOrdersWarehouseNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $attachment;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($attachment, $subject) {
        $this->attachment = $attachment;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('emails.wms.transferred')
            ->subject($this->subject)
            ->attach($this->attachment);
    }
}
