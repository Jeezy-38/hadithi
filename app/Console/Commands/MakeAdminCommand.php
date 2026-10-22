<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    protected $signature = 'app:make-admin {email : Barua pepe ya mtumiaji unayetaka awe admin} {--revoke : Ondoa uwezo wa admin}';

    protected $description = 'Weka au ondoa haki za admin kwa mtumiaji kupitia barua pepe yake';

    public function handle(): int
    {
        $email = trim($this->argument('email'));
        $revoke = (bool) $this->option('revoke');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Mtumiaji mwenye barua pepe [{$email}] hakupatikana!");
            $this->line("Hakikisha amejisajili au jaribu tena.");
            return self::FAILURE;
        }

        $user->is_admin = !$revoke;
        $user->save();

        if ($revoke) {
            $this->warn("Haki za admin zimeondolewa kwa: {$user->name} ({$user->email})");
        } else {
            $this->info("Hongera! Mtumiaji {$user->name} ({$user->email}) sasa ni ADMIN kikamilifu.");
        }

        return self::SUCCESS;
    }
}
