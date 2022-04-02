<?php

namespace App\Service\Zotero;

use App\DataContainers\Zotero\User;
use ZoteroApi\Endpoint\AbstractEndpoint;
use ZoteroApi\Endpoint\Collections;
use ZoteroApi\Endpoint\Items;
use ZoteroApi\Exceptions\ZoteroAccessDeniedException;
use ZoteroApi\Exceptions\ZoteroBadRequestException;
use ZoteroApi\Exceptions\ZoteroConnectionException;
use ZoteroApi\Exceptions\ZoteroEndpointNotFoundException;
use ZoteroApi\Exceptions\ZoteroInvalidChainingException;
use ZoteroApi\Source\AbstractSource;
use ZoteroApi\Source\KeysSource;
use ZoteroApi\ZoteroApi;

class LoadZoteroCollections
{

    /**
     * @throws ZoteroConnectionException
     * @throws ZoteroInvalidChainingException
     * @throws ZoteroAccessDeniedException
     * @throws ZoteroBadRequestException
     * @throws ZoteroEndpointNotFoundException
     */
    public function load(string $apiKey, AbstractSource $source, string $id = AbstractEndpoint::TOP)
    {
        $api = new ZoteroApi($apiKey, $source);
        $api->setEndpoint(new Collections(AbstractEndpoint::TOP));
        $api->run();
        $cols = $api->getBody();
        for ($i = 0; $i < sizeof($cols); $i++) {
            $sub = $this->loadSubCollections($api, $cols[$i]["key"]);
            if (isset($sub) && sizeof($sub) > 0) {
                $cols[$i]["subcollections"] = $sub;
            }
        }
        return $cols;
    }

    /**
     * @throws ZoteroConnectionException
     * @throws ZoteroInvalidChainingException
     * @throws ZoteroBadRequestException
     * @throws ZoteroAccessDeniedException
     * @throws ZoteroEndpointNotFoundException
     */
    private function loadSubCollections(ZoteroApi $api, string $id)
    {
        $api->setEndpoint(
            (new Collections($id))
                ->setEndpoint(new Collections(AbstractEndpoint::ALL))
        );
        $api->run();
        $cols = $api->getBody();
        for($i=0;$i<sizeof($cols);$i++){
            $subs = $this->loadSubCollections($api,$cols[$i]["key"]);
            if(isset($subs) && sizeof($subs) > 0){
                $cols[$i]["subcollections"] = $subs;
            }
        }
        return $cols;
    }

    /**
     * Returns User DataContainer for a API key
     *
     * @param string $apiKey
     * @return ?User
     * @throws ZoteroAccessDeniedException
     * @throws ZoteroBadRequestException
     * @throws ZoteroConnectionException
     * @throws ZoteroEndpointNotFoundException
     */
    public function getUserByAPI(string $apiKey): ?User
    {
        $api = new ZoteroApi($apiKey, new KeysSource($apiKey));
        $api->run();
        return User::createFromBody($api->getBody());
    }

}