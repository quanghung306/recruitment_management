<?php

namespace App\Console\Commands;

use App\Models\Candidate;
use App\Models\Skill;
use Illuminate\Console\Command;

class MigrateCandidateSkills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-candidate-skills';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $candidates = Candidate::whereNotNull('skills')->get();

        foreach ($candidates as $candidate) {
            $skills = json_decode($candidate->skills, true);

            foreach ($skills as $skillName) {
                $skill = Skill::firstOrCreate(['name' => trim($skillName)]);
                $candidate->skills()->attach($skill->id);
            }
        }

        $this->info('Migrated skills successfully!');
    }
}
