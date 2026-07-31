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

namespace CORS\Bundle\WebCareBundle\Util\Constant;

/**
 * @internal
 */
final class PermissionConstants
{
    /**
     * Own permission, created by the bundle installer.
     *
     * The classic UI gated on `user.isAllowed('plugins')`, but that permission does
     * not exist in Pimcore 12 — it is missing from `users_permission_definitions`.
     * Studio's UserPermissionVoter only supports attributes it finds in that table,
     * so an unknown one is never granted and every request ends in "Access Denied".
     */
    public const string WEB_CARE_SETTINGS = 'web_care_settings';
}
