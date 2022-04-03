<?php

namespace App\Command;

use App\Service\Zotero\LoadZoteroCollections;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use ZoteroApi\Exceptions\ZoteroAccessDeniedException;
use ZoteroApi\Exceptions\ZoteroBadRequestException;
use ZoteroApi\Exceptions\ZoteroConnectionException;
use ZoteroApi\Exceptions\ZoteroEndpointNotFoundException;

#[AsCommand(
    name: 'app:zotero:get-user',
    description: 'Returns userID for given API key',
)]
class ZoteroGetUserCommand extends Command
{

    private LoadZoteroCollections $collections;

    public function __construct(LoadZoteroCollections $collections) {
        parent::__construct();
        $this->collections = $collections;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('api-key', InputArgument::OPTIONAL, 'API key generated in Zotero settings')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $apiKey = $input->getArgument('api-key');

        if ($apiKey) {
            $io->note(sprintf('API Key: %s', $apiKey));
        }

        try {
            $user = $this->collections->getUserByAPI($apiKey);
            $io->success(sprintf("userID: %s",$user->userID));
            return Command::SUCCESS;
        } catch (ZoteroAccessDeniedException $e) {
            $io->error("Invalid API Key!");
        } catch (ZoteroConnectionException $e) {
            $io->error("Unable to connect to API endpoint!");
        } catch (ZoteroEndpointNotFoundException | ZoteroBadRequestException $e) {
            $io->error("Unable to retrieve data!");
        }

        return Command::FAILURE;
    }
}
