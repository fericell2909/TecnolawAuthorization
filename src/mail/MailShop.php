<?php
namespace Tecnolaw\Authorization\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailShop extends Mailable
{

	use Queueable, SerializesModels;

    protected $data;

    public function __construct($data = null, $offTemplate)
    {
        $this->data = $data;
        $this->subject= $data['data']->subject;
        if (isset($this->data['data']->order)) {
        }

    }

    public function build()
    {
        $view = 'TecnolawAuth::mails.template';
        return $this->view($view)->with($this->data);
    }
}
