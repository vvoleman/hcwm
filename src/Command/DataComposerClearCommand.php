<?php

namespace App\Command;

use App\Service\Geojson\DataComposerUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'data-composer:clear',
    description: 'Clears data composer cache',
)]
class DataComposerClearCommand extends Command
{
    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

		$util = new DataComposerUtil();
		$result = $util->clearCache();

		if ($result) {
			$io->success('Cache cleared');
			return Command::SUCCESS;
		}

		$io->error('Some of the files could not be deleted');
		return Command::FAILURE;
    }
}
