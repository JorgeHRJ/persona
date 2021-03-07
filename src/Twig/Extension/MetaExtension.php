<?php

namespace App\Twig\Extension;

use App\Entity\Post;
use App\Entity\Profile;
use App\Entity\Tag;
use App\Service\ImageService;
use App\Twig\Tag\Meta\MetaTokenParser;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MetaExtension extends AbstractExtension
{
    const OG_TYPE = 'type';
    const OG_LOCALE = 'locale';
    const OG_SITE_NAME = 'site_name';
    const OG_TITLE = 'title';
    const OG_URL = 'url';
    const OG_DESCRIPTION = 'description';
    const OG_IMAGE = 'image';
    const OG_TAGS = [
        self::OG_TYPE,
        self::OG_LOCALE,
        self::OG_SITE_NAME,
        self::OG_TITLE,
        self::OG_URL,
        self::OG_DESCRIPTION,
        self::OG_IMAGE
    ];

    const TWITTER_CARD_CARD = 'card';
    const TWITTER_CARD_TITLE = 'title';
    const TWITTER_CARD_DESCRIPTION = 'description';
    const TWITTER_CARD_SITE = 'site';
    const TWITTER_CARD_IMAGE = 'image';
    const TWITTER_CARD_CREATOR = 'creator';
    const TWITTER_CARD_TAGS = [
        self::TWITTER_CARD_CARD,
        self::TWITTER_CARD_TITLE,
        self::TWITTER_CARD_DESCRIPTION,
        self::TWITTER_CARD_SITE,
        self::TWITTER_CARD_IMAGE,
        self::TWITTER_CARD_CREATOR
    ];

    private $templating;
    private $imageService;
    private $assetHelper;
    private $urlHelper;
    private $router;
    private $requestStack;

    /** @var object|null */
    private $model;

    /** @var string */
    private $description;

    /** @var string */
    private $keywords;

    /** @var string */
    private $author;

    /** @var string */
    private $publishedTime;

    /** @var array */
    private $og;

    /** @var array */
    private $twitter;

    public function __construct(
        Environment $templating,
        Packages $assetHelper,
        UrlHelper $urlHelper,
        RouterInterface $router,
        RequestStack $requestStack,
        ImageService $imageService
    ) {
        $this->templating = $templating;
        $this->assetHelper = $assetHelper;
        $this->urlHelper = $urlHelper;
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->imageService = $imageService;
        $this->description = '';
        $this->keywords = '';
        $this->author = '';
        $this->publishedTime = '';
        $this->og = [];
        $this->twitter = [];
        $this->model = null;
    }

    public function getTokenParsers(): array
    {
        return [
            new MetaTokenParser(),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_meta_tags', [$this, 'getMetaTags']),
            new TwigFunction('get_canonical', [$this, 'getCanonical'])
        ];
    }

    /**
     * @param array $config
     */
    public function configure(array $config): void
    {
        if (isset($config['model'])) {
            $this->model = $config['model'];
            $this->processModel();
            return;
        }

        if (isset($config['description'])) {
            $this->description = $config['description'];
        }

        if (isset($config['keywords'])) {
            $this->keywords = $config['keywords'];
        }

        if (isset($config['author'])) {
            $this->author = $config['author'];
        }

        if (isset($config['published_time'])) {
            $this->publishedTime = $config['published_time'];
        }

        if (isset($config['og'])) {
            foreach (self::OG_TAGS as $ogTag) {
                if (isset($config['og'][$ogTag])) {
                    $this->og[sprintf('og:%s', $ogTag)] = $config['og'][$ogTag];
                }
            }
        }

        if (isset($config['twitter'])) {
            foreach (self::TWITTER_CARD_TAGS as $twitterTag) {
                if (isset($config['twitter'][$twitterTag])) {
                    $this->twitter[sprintf('twitter:%s', $twitterTag)] = $config['twitter'][$twitterTag];
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getCanonical(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request instanceof Request) {
            return '';
        }

        return $request->getUri();
    }

    public function getMetaTags(): string
    {
        $metaTags = [];
        $extraTags = [];

        if ($this->description !== '') {
            $metaTags['description'] = $this->description;
        }

        if ($this->keywords !== '') {
            $metaTags['keywords'] = $this->keywords;
        }

        if ($this->author !== '') {
            $metaTags['author'] = $this->author;
        }

        if ($this->publishedTime !== '') {
            $extraTags['article:published_time'] = $this->publishedTime;
        }

        return $this->templating->render('site/components/meta.html.twig', [
            'meta_tags' => $metaTags,
            'og_tags' => $this->og,
            'twitter_card_tags' => $this->twitter,
            'extra_tags' => $extraTags
        ]);
    }

    private function processModel(): void
    {
        list(, , $entity) = explode('\\', get_class($this->model));
        switch ($entity) {
            case 'Post':
                if ($this->model instanceof Post) {
                    $this->processPost($this->model);
                }
                break;
            default:
                break;
        }
    }

    private function processPost(Post $post): void
    {
        $tagNames = array_map(function (Tag $tag) {
            return $tag->getName();
        }, $post->getTags()->toArray());
        $tagNames = implode(', ', $tagNames);

        $imageFile = $this->imageService->getImage('main', $post);
        $imageSrc = $this->urlHelper->getAbsoluteUrl($this->assetHelper->getUrl($imageFile));

        $this->description = $post->getSummary();
        $this->keywords = $tagNames;
        $this->author = $post->getAuthor()->getName();
        $this->publishedTime = $post->getPublishedAt()->format('c');

        $this->og[sprintf('og:%s', self::OG_TITLE)] = $post->getTitle();
        $this->og[sprintf('og:%s', self::OG_DESCRIPTION)] = $post->getSummary();
        $this->og[sprintf('og:%s', self::OG_TYPE)] = 'article';
        $this->og[sprintf('og:%s', self::OG_IMAGE)] = $imageSrc;
        $this->og[sprintf('og:%s', self::OG_URL)]
            = $this->router->generate(
                'site_blog_post',
                ['slug' => $post->getSlug()],
                RouterInterface::ABSOLUTE_URL
        );
        $this->og[sprintf('og:%s', self::OG_LOCALE)] = 'es_ES';

        $this->twitter[sprintf('twitter:%s', self::TWITTER_CARD_CARD)] = 'summary_large_image';
        $this->twitter[sprintf('twitter:%s', self::TWITTER_CARD_TITLE)] = $post->getTitle();
        $this->twitter[sprintf('twitter:%s', self::TWITTER_CARD_DESCRIPTION)] = $post->getSummary();
        $this->twitter[sprintf('twitter:%s', self::TWITTER_CARD_IMAGE)] = $imageSrc;
        if ($post->getAuthor()->getProfile() instanceof Profile) {
            $this->twitter[sprintf('twitter:%s', self::TWITTER_CARD_CREATOR)]
                = sprintf('@%s', $post->getAuthor()->getProfile()->getTwitter());
            $this->twitter[sprintf('twitter:%s', self::TWITTER_CARD_SITE)]
                = sprintf('@%s', $post->getAuthor()->getProfile()->getTwitter());
        }
    }
}
