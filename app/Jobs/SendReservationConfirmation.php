<?php

namespace Javan\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Javan\AppMailer;
use Javan\Reservation;

class SendReservationConfirmation extends Job implements ShouldQueue
{
	use InteractsWithQueue, SerializesModels;
	protected $reservation;

	/**
	 * Create a new job instance.
	 *
	 * @param \Javan\Reservation $reservation
	 */
	public function __construct(Reservation $reservation)
	{
		$this->reservation = $reservation;
	}

	/**
	 * Create a new job instance.
	 *
	 * @param \Javan\AppMailer $mailer
	 */
	public function handle(AppMailer $mailer)
	{
		$mailer->sendEmailTo(
			$this->reservation->user->email,
			$this->reservation->load('user')->toArray(),
			'Your Booking Confirmation',
			'emails.reservation-confirmation'
		);
	}
}
