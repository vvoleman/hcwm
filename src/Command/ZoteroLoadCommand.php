<?php

namespace App\Command;

use App\Exception\BadLanguageFormatException;
use App\Service\Zotero\Exception\Entity\InvalidLanguageException;
use App\Service\Zotero\ZoteroSyncer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:zotero:load',
    description: 'Loads data from Zotero to database',
)]
class ZoteroLoadCommand extends Command
{

   public function __construct(
		private ZoteroSyncer $zoteroSyncer,
	) {
		parent::__construct();
	}

    protected function configure(): void
    {
        $this
            ->addArgument('apiKey', InputArgument::OPTIONAL, 'API key for Zotero')
            ->addArgument("sourceId",InputArgument::OPTIONAL,"ID of source (group/user)")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $apiKey = $input->getArgument('apiKey');
        $sourceId = $input->getArgument("sourceId");
        try {
			$io->info('Starting Zotero sync at ' . date('Y-m-d H:i:s'));
			$this->zoteroSyncer->sync($apiKey, $sourceId);

			$io->success("Zotero data loaded");
			return Command::SUCCESS;
		} catch (BadLanguageFormatException|InvalidLanguageException $e) {
			$io->error($e->getMessage());
        }catch (\Exception $e){
            $io->error(sprintf("Loading failed: %s",$e->getMessage()));
        }
		return Command::FAILURE;

    }
}
