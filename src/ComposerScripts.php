<?php
namespace Vncore\Core;

use Composer\Script\Event;

class ComposerScripts
{
    public static function postInstall(Event $event)
    {
        file_exists('storage/vncore_not_run') || touch('storage/vncore_not_run');
    }

    public static function postUpdate(Event $event)
    {
        file_exists('storage/vncore_not_run') || touch('storage/vncore_not_run');
    }
}