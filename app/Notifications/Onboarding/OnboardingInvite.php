<?php

namespace App\Notifications\Onboarding;

use App\Channels\FirebaseChannel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OnboardingInvite extends Notification
{
    use Queueable;

    private User $user;
    private string $title;
    private string $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->title = 'Welcome to the Team! Let\'s Get Started';
        $this->message = 'Hi ' . $user->first_name . ', we are excited to have you on board! Please complete your pre-onboarding formalities by filling out the form.';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->greeting('Hi ' . $this->user->first_name . ',')
            ->line($this->message)
            ->line('Please complete the onboarding form within 3 days to avoid your account getting deactivated.')
            ->action('Start Onboarding', url('/login'))
            ->line('Welcome aboard!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'onboarding_deadline' => $this->user->onboarding_deadline,
        ];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'user_id' => $this->user->id,
        ];
    }
}
