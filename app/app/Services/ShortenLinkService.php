<?php

namespace App\Services;

use App\Models\Link;
use App\Repositories\LinkRepository;
use Illuminate\Support\Collection;

class ShortenLinkService
{
    protected LinkRepository $linkRepository;

    /**
     * NativeValidationService constructor.
     */
    public function __construct()
    {
        $this->linkRepository = new LinkRepository();
    }

    /**
     * @param array $params
     * @return Collection
     */
    public function getLinksByParams(array $params): Collection
    {
        return $this->linkRepository->getByParams($params);
    }

    /**
     * @return Collection
     */
    public function getLinks(): Collection
    {
        return $this->linkRepository->getAll();
    }

    /**
     * @param array $link
     * @return bool
     */
    public function updateClicks(array $link): bool
    {
        $id = $link['id'];

        return $this->linkRepository->update($id, ['clicks' => $link['clicks'] + 1]);
    }

    /**
     * @param string $link
     * @return Link
     */
    public function processLink(string $link): Link
    {
        $shortedLink = $this->hashWithSHA($link);
        $shortedLink = $this->encodeLink($shortedLink, 7);

        return $this->linkRepository->save($link, $shortedLink, 0);
    }

    /**
     * @param string $link
     * @return string
     */
    private function hashWithSHA(string $link): string
    {
        return hash('sha256', $link);
    }

    /**
     * @param string $link
     * @param int $symbolsNumber
     * @return string
     */
    private function encodeLink(string $link, int $symbolsNumber): string
    {
        return substr(base64_encode($link), 0, $symbolsNumber);
    }
}
