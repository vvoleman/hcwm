<?php

namespace App\DataContainers\Zotero;

class User
{
    public string $userID;
    public string $username;
    public string $displayName;

    /**
     * Creates instance from ZoteroAPI response body
     *
     * @param array $body
     * @return User|null
     */
    public static function createFromBody(array $body): ?User
    {
        $required = ["userID","username","displayName"];

        $user = new self;

        foreach ($required as $attribute) {
            if(!isset($body[$attribute])){
                return null;
            }
            $user->$attribute = $body[$attribute];
        }

        return $user;
    }

}