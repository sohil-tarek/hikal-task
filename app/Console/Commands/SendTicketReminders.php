<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use Carbon\Carbon;
use App\Mail\TicketReminder;
use Illuminate\Support\Facades\Mail;

class SendTicketReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Send reminders for pending tickets';

    /**
     * Execute the console command.
     *
     * @return int
         */
        public function handle()
        {
            // Daily reminder for pending tickets
            $this->sendDailyReminders();

            // Three times a day reminder for tickets with today's deadline
            $this->sendDeadlineReminders();

            $this->info('Ticket reminders sent successfully.');
        }

        //private function to send reminders
        private function sendDailyReminders()
        {
            $pendingTickets = Ticket::where('status', 'pending')->where('processed', false)->get();

            foreach ($pendingTickets as $ticket) {
                $this->sendReminder($ticket);
            }
        }

        //private function to send deadline reminders
        private function sendDeadlineReminders()
        {
            $todayDeadlineTickets = Ticket::whereDate('deadline', Carbon::today())->where('processed', false)->get();

            foreach ($todayDeadlineTickets as $ticket) {
                $this->sendReminder($ticket);
            }
        }

        //private function to send reminders
        private function sendReminder($ticket)
        {
            $teamMemberEmail = $ticket->user->email;
            Mail::to($teamMemberEmail)->send(new TicketReminder($ticket));

            // Mark the ticket as processed to avoid repetition
            $ticket->update(['processed' => true]);
        }





}
