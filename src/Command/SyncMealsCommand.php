<?php

namespace App\Command;

use App\Service\Meal\MealSync;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:sync-meals',
    description: 'Syncing meals',
)]
class SyncMealsCommand extends Command
{
    public function __construct(
        private readonly MealSync $mealSync,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->mealSync->execute();

        return Command::SUCCESS;
    }
}
