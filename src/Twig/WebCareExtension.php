<?php

declare(strict_types=1);

namespace CORS\Bundle\WebCareBundle\Twig;

use CORS\Bundle\WebCareBundle\Entity\WebCareSite;
use CORS\Bundle\WebCareBundle\Repository\WebCareSiteRepository;
use Pimcore\Model\Site;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class WebCareExtension extends AbstractExtension
{
    protected $repository;
    protected $twig;

    public function __construct(
        WebCareSiteRepository $repository,
        Environment $twig
    ) {
        $this->repository = $repository;
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('cors_webcare_config', [$this, 'findConfig']),
            new TwigFunction('cors_webcare_cookie_banner_css', [$this, 'cookieBannerCss']),
            new TwigFunction('cors_webcare_cookie_banner_js', [$this, 'cookieBannerJs']),
            new TwigFunction('cors_webcare_cookie_banner', [$this, 'renderCookieBanner'], ['is_safe' => ['html']]),
            new TwigFunction('cors_webcare_privacy_js', [$this, 'privacyStatementJs']),
            new TwigFunction('cors_webcare_privacy', [$this, 'renderPrivacyStatement'], ['is_safe' => ['html']]),
            new TwigFunction('cors_webcare_privacy_v2_js', [$this, 'privacyStatementJsV2']),
            new TwigFunction('cors_webcare_privacy_v2', [$this, 'renderPrivacyStatementV2'], ['is_safe' => ['html']]),
            new TwigFunction('cors_webcare_imprint_js', [$this, 'imprintJs']),
            new TwigFunction('cors_webcare_imprint', [$this, 'renderImprint'], ['is_safe' => ['html']]),
            new TwigFunction('cors_webcare_imprint_v2_js', [$this, 'imprintJsV2']),
            new TwigFunction('cors_webcare_imprint_v2', [$this, 'renderImprintV2'], ['is_safe' => ['html']]),
        ];
    }

    public function findConfig(bool $active = true): ?WebCareSite
    {
        if (Site::isSiteRequest()) {
            return $this->repository->findForSite(Site::getCurrentSite(), $active);
        }

        return $this->repository->findDefault($active);
    }

    public function webCareIntegration(string $filename, string $extension, bool $active = true)
    {
        $config = $this->findConfig($active);

        if (!$config) {
            return null;
        }

        return $this->createUrl($config, $filename, $extension);
    }

    private function createUrl(WebCareSite $config, string $filename, string $extension)
    {
        if ($config->getWebsiteId()) {
            return sprintf(
                'https://webcache-eu.datareporter.eu/c/%s/%s/%s/%s.%s',
                $config->getClientId(),
                $config->getOrganizationId(),
                $config->getWebsiteId(),
                $filename,
                $extension
            );
        }

        return sprintf(
            'https://webcache-eu.datareporter.eu/c/%s/%s/%s.%s',
            $config->getClientId(),
            $config->getOrganizationId(),
            $filename,
            $extension
        );
    }

    private function createImprintUrl(WebCareSite $config, string $filename, string $extension)
    {
        return sprintf(
            'https://webcache-eu.datareporter.eu/c/%s/%s/%s.%s',
            $config->getClientId(),
            $config->getOrganizationId(),
            $filename,
            $extension
        );
    }

    public function renderCookieBanner(bool $active = true)
    {
        $config = $this->findConfig($active);

        if (!$config) {
            return null;
        }

        $css = $this->createUrl($config, 'banner', 'css');
        $js = $this->createUrl($config, 'banner', 'js');

        return $this->twig->render('@CORSWebCare/banner.html.twig', [
            'css' => $css,
            'js' => $js,
        ]);
    }

    public function renderPrivacyStatement(bool $active = true)
    {
        $config = $this->findConfig($active);

        if (!$config) {
            return null;
        }

        $js = $this->createUrl($config, 'privacynotice', 'js');
        $js_noscript = $this->createUrl($config, 'privacynotice_noscript', 'js');

        return $this->twig->render('@CORSWebCare/privacy.html.twig', [
            'js' => $js,
            'js_noscript' => $js_noscript,
        ]);
    }

    public function renderPrivacyStatementV2(bool $active = true)
    {
        $config = $this->findConfig($active);

        if (!$config) {
            return null;
        }

        $js = $this->createUrl($config, 'privacynotice_v2', 'js');
        $js_noscript = $this->createUrl($config, 'privacynotice_noscript', 'js');

        return $this->twig->render('@CORSWebCare/privacy_v2.html.twig', [
            'js' => $js,
            'js_noscript' => $js_noscript,
        ]);
    }

    public function renderImprint(bool $active = true)
    {
        $config = $this->findConfig($active);

        if (!$config) {
            return null;
        }

        $js = $this->createImprintUrl($config, 'imprint', 'js');
        $js_noscript = $this->createImprintUrl($config, 'imprint_noscript', 'js');

        return $this->twig->render('@CORSWebCare/imprint.html.twig', [
            'js' => $js,
            'js_noscript' => $js_noscript,
        ]);
    }

    public function renderImprintV2(bool $active = true)
    {
        $config = $this->findConfig($active);

        if (!$config) {
            return null;
        }

        $js = $this->createImprintUrl($config, 'imprint_v2', 'js');
        $js_noscript = $this->createImprintUrl($config, 'imprint_noscript', 'js');

        return $this->twig->render('@CORSWebCare/imprint_v2.html.twig', [
            'js' => $js,
            'js_noscript' => $js_noscript,
        ]);
    }

    public function cookieBannerCss(bool $active = true)
    {
        return $this->webCareIntegration('banner', 'css', $active);
    }

    public function cookieBannerJs(bool $active = true)
    {
        return $this->webCareIntegration('banner', 'js', $active);
    }

    public function privacyStatementJs(bool $active = true)
    {
        return $this->webCareIntegration('privacynotice', 'js', $active);
    }

    public function imprintJs(bool $active = true)
    {
        return $this->webCareIntegration('imprint', 'js', $active);
    }

    public function privacyStatementJsV2(bool $active = true)
    {
        return $this->webCareIntegration('privacynotice_v2', 'js', $active);
    }

    public function imprintJsV2(bool $active = true)
    {
        return $this->webCareIntegration('imprint_v2', 'js', $active);
    }
}
