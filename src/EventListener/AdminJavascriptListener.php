<?php

declare(strict_types=1);

/*
 * Austrian Standards Operations GmbH
 *
 * This software is available under the GNU General Public License version 3 (GPLv3).
 *
 * @copyright  Copyright (c) Austrian Standards Operations GmbH (https://www.austrian-standards.at)
 * @license    GPLv3
 */

namespace CORS\Bundle\WebCareBundle\EventListener;

use Pimcore\Event\BundleManager\PathsEvent;
use Pimcore\Event\BundleManagerEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class AdminJavascriptListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BundleManagerEvents::JS_PATHS => 'getAdminJavascript',
            BundleManagerEvents::CSS_PATHS => 'getAdminCss',
            BundleManagerEvents::EDITMODE_JS_PATHS => 'getEditmodeAdminJavascript',
            BundleManagerEvents::EDITMODE_CSS_PATHS => 'getEditmodeAdminCSS',
        ];
    }

    public function getAdminJavascript(PathsEvent $event): void
    {
        $event->setPaths(array_merge($event->getPaths(), [
            '/bundles/corswebcare/pimcore/js/startup.js',
            '/bundles/corswebcare/pimcore/js/settings.js',
        ]));
    }

    public function getAdminCss(PathsEvent $event): void
    {
        $event->setPaths(array_merge($event->getPaths(), [
            '/bundles/corswebcare/pimcore/css/cors.css',
        ]));
    }

    public function getEditmodeAdminJavascript(PathsEvent $event): void
    {
        $event->setPaths(array_merge($event->getPaths(), []));
    }

    public function getEditmodeAdminCSS(PathsEvent $event): void
    {
        $event->setPaths(array_merge($event->getPaths(), []));
    }
}
