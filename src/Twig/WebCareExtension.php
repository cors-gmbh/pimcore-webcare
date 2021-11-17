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
    )
    {
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

    public function findConfig(): ?WebCareSite
    {
        if (Site::isSiteRequest()) {
            return $this->repository->findForSite(Site::getCurrentSite());
        }

        return $this->repository->findDefault();
    }

    public function webCareIntegration(string $filename, string $extension)
    {
        $config = $this->findConfig();

        if (!$config) {
            return null;
        }

        return $this->createUrl($config, $filename, $extension);
    }

    private function createUrl(WebCareSite $config, string $filename, string $extension)
    {
        if ($config->getWebsiteId()) {
            return sprintf(
                'https://webcache.datareporter.eu/c/%s/%s/%s/%s.%s',
                $config->getClientId(),
                $config->getOrganizationId(),
                $config->getWebsiteId(),
                $filename,
                $extension,
            );
        }

        return sprintf(
            'https://webcache.datareporter.eu/c/%s/%s/%s.%s',
            $config->getClientId(),
            $config->getOrganizationId(),
            $filename,
            $extension,
        );
    }

    private function createImprintUrl(WebCareSite $config, string $filename, string $extension)
    {
        return sprintf(
            'https://webcache.datareporter.eu/c/%s/%s/%s.%s',
            $config->getClientId(),
            $config->getOrganizationId(),
            $filename,
            $extension,
        );
    }

    public function renderCookieBanner()
    {
        $config = $this->findConfig();

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

    public function renderPrivacyStatement()
    {
        $config = $this->findConfig();

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

    public function renderPrivacyStatementV2()
    {
        $config = $this->findConfig();

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

    public function renderImprint()
    {
        $config = $this->findConfig();

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

    public function renderImprintV2()
    {
        $config = $this->findConfig();

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

    public function cookieBannerCss()
    {
        return $this->webCareIntegration('banner', 'css');
    }

    public function cookieBannerJs()
    {
        return $this->webCareIntegration('banner', 'js');
    }

    public function privacyStatementJs()
    {
        return $this->webCareIntegration('privacynotice', 'js');
    }

    public function imprintJs()
    {
        return $this->webCareIntegration('imprint', 'js');
    }

    public function privacyStatementJsV2()
    {
        return $this->webCareIntegration('privacynotice_v2', 'js');
    }

    public function imprintJsV2()
    {
        return $this->webCareIntegration('imprint_v2', 'js');
    }
}
