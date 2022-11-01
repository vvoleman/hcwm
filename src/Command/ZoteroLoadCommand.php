<?php

namespace App\Command;

use App\Exception\BadLanguageFormatException;
use App\Service\Zotero\LoadZoteroCollections;
use App\Service\Zotero\LoadZoteroItems;
use App\Service\Zotero\Updated\Exception\Entity\InvalidLanguageException;
use App\Service\Zotero\Updated\ZoteroSyncer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use ZoteroApi\Source\UsersSource;
use ZoteroApi\ZoteroApi;

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
            ->addArgument('apiKey', InputArgument::REQUIRED, 'API key for Zotero')
            ->addArgument("sourceId",InputArgument::REQUIRED,"ID of source (group/user)")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $apiKey = $input->getArgument('apiKey');
        $sourceId = $input->getArgument("sourceId");

        try {
			$io->progressStart(1);
			$this->zoteroSyncer->sync($apiKey, $sourceId);
			$io->progressAdvance();

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
