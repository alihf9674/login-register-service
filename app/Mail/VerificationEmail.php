<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.verification-email')->with([
            'link' => $this->generateUrl(),
            'name' => $this->user->name
        ]);
    }

    protected function generateUrl(): string
    {
        return URL::temporarySignedRoute('auth.email.verify', now()->addMinutes(120), ['email' => $this->user->email]);
    }
}
