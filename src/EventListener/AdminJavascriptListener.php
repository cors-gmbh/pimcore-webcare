<?php

declare(strict_types=1);

/**
 * CORS GmbH.
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    https://www.cors.gmbh/license     GPLv3 and PCL
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
