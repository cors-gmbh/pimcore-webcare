CORS WebCare Bundle
--------

WebCare - DataReporter Pimcore Integration (https://www.cookiebanner.at/)

# Installation

```bash
composer require cors/web-care

bin/console pimcore:bundle:enable CORSWebCareBundle
bin/console pimcore:bundle:install CORSWebCareBundle
```

Pimcore Studio (`pimcore/studio-backend-bundle` and `pimcore/studio-ui-bundle`) is a hard
requirement: the classic ExtJS admin UI no longer exists in Pimcore 2026, so this bundle ships a
Studio UI only.

# Configuration

 - Open Pimcore Studio
 - Open System -> Webcare Settings
 - Configure Client ID, Configuration ID and Website ID per Site or for your "Home" Site.

The menu entry is gated on the `web_care_settings` user permission, which the bundle installer
creates. Users without that permission neither see the entry nor may call the API.

# Usage in Twig

This bundle provides several ways of integration. It comes with several twig functions:

- **cors_webcare_config** Load the corresponding instance of `CORS\Bundle\WebCareBundle\Entity\WebCareSite` based on the current site you are on. Returns null when no config is set.
- **cors_webcare_cookie_banner_css** Gives you the full path to Webcare's banner.css file
- **cors_webcare_cookie_banner_js** Gives you the full path to Webcare's banner.js file
- **cors_webcare_cookie_banner** Renders the banner.css and banner.js directly
- **cors_webcare_privacy_js** Gives you the full path to Webcare's privacystatement.js file
- **cors_webcare_privacy** Renders the privacystatement.js directly
- **cors_webcare_privacy_v2_js** Gives you the full path to Webcare's privacystatement_v2.js file
- **cors_webcare_privacy_v2** Renders the privacystatement_v2.js directly
- **cors_webcare_imprint_js** Gives you the full path to Webcare's imprint.js file
- **cors_webcare_imprint** Renders the cors_webcare_imprint_js.js directly
- **cors_webcare_imprint_v2_js** Gives you the full path to Webcare's imprint_v2.js file
- **cors_webcare_imprint_v2**: Renders the cors_webcare_imprint_v2.js directly
- **cors_webcare_webdoc_v2_js**: Gives you the full path to Webcare's webdoc_v2.js file
- **cors_webcare_webdoc_v2**: Renders the cors_webcare_webdoc_v2.js directly

# Development

The repository doubles as a runnable Pimcore application on **Pimcore 2026 with Studio** (the
CORS bundle template: `Kernel.php`, `bin/console`, `config/`, `dev/`, `docker-compose.yaml`
including the shared `dev-compose` stack). The bundle itself is `src/`
(`CORSWebCareBundle::getPath()`, with `Resources/` inside); everything else at the repository
root only serves the dev harness, the Studio frontend build or the CI, and `.gitattributes` keeps
it out of the distributed composer package.

```bash
docker compose up -d
docker compose exec -T php composer install
docker compose exec -T php vendor/bin/pimcore-install \
    --install-profile='App\InstallProfile\StudioInstallProfile' \
    --admin-username=admin --admin-password=admin --no-interaction
docker compose exec -T php bin/console pimcore:bundle:install CORSWebCareBundle
docker compose exec -T php bin/console assets:install --symlink --relative public
```

Studio is then served at `https://cors-pimcore-webcare.dev.localhost/pimcore-studio/`.

The install profile (`dev/InstallProfile/StudioInstallProfile.php`) declares the bundles and
infrastructure the harness needs (Studio backend/UI, generic data index + OpenSearch, Mercure,
Doctrine messenger transport). Connection defaults point at the dev-compose services and live
in `.env`; the Pimcore bundles are registered in `config/bundles.php`, the bundle under
development in `Kernel.php`.

Pimcore 2026 refuses to boot without a registered instance: the instance identifier
(`pimcore-webcare`) is committed in `.env`, and `PIMCORE_ENCRYPTION_SECRET` plus
`PIMCORE_PRODUCT_KEY` go into your uncommitted `.env.local`. Register the identifier at
[license.pimcore.com](https://license.pimcore.com) to obtain them; CI reads the same three
values from the repository secrets `PIMCORE_ENCRYPTION_SECRET`, `PIMCORE_INSTANCE_IDENTIFIER`
and `PIMCORE_PRODUCT_KEY` (`.github/workflows/static.yaml`, written to `.env.local` by the
shared `php-test` workflow before the kernel boots).

Static checks run the same way as in CI. This repository is public, so the shared rule set comes
from the public `coreshop/test-setup` package instead of the private `cors/dev`:

```bash
vendor/bin/ecs check src
vendor/bin/phpstan analyse
vendor/bin/psalm src
```

The Studio plugin is built with `npm run build` (see `package.json`); the build lands as an
archive in `src/Resources/build-dist/` and is committed by the `Studio Frontend Build` workflow.

## License

MIT, see [LICENSE.md](LICENSE.md).
