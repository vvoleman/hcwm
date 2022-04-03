<?php

namespace App\Command;

use App\Service\Zotero\LoadZoteroCollections;
use App\Service\Zotero\LoadZoteroItems;
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

    private LoadZoteroCollections $loadZoteroCollections;
    private LoadZoteroItems $loadZoteroItems;

    public function __construct(LoadZoteroCollections $loadZoteroCollections, LoadZoteroItems $loadZoteroItems) {
        parent::__construct();
        $this->loadZoteroCollections = $loadZoteroCollections;
        $this->loadZoteroItems = $loadZoteroItems;
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

        $api = new ZoteroApi($apiKey,new UsersSource($sourceId));

        try{
            $io->progressStart(2);
            $this->loadZoteroCollections->load($apiKey,new UsersSource($sourceId));
            $io->progressAdvance(1);
            $this->loadZoteroItems->loadAllItems($api);
            $io->progressAdvance(2);
        }catch (\Exception $e){
            $io->error(sprintf("Loading failed: %s",$e->getMessage()));
            return Command::FAILURE;
        }
        $io->success("Zotero data loaded");
        return Command::SUCCESS;
    }
}
