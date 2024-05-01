<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessLinkRequest;
use App\Services\ShortenLinkService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class LinkShortenerController extends Controller
{
    protected ShortenLinkService $linkService;

    /**
     * @param ShortenLinkService $linkService
     */
    public function __construct(ShortenLinkService $linkService)
    {
        $this->linkService = $linkService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Application
     */
    public function start(): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Application
    {
        $links = $this->linkService->getLinks();

        return view('welcome', ['links' => $links]);
    }

    /**
     * @param ProcessLinkRequest $request
     * @return RedirectResponse
     */
    public function process(ProcessLinkRequest $request): RedirectResponse
    {
        try {
            $linkObject = $this->linkService->processLink($request->get('link'));

            return redirect()->back()->with(
                'success_message',
                'Your short link: <a href="'.url($linkObject->short_url).'">'.url($linkObject->short_url).'</a>'
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to process data.']);
        }
    }

    /**
     * @param string $shortLink
     * @return RedirectResponse
     */
    public function redirectToLink(string $shortLink): RedirectResponse
    {
        $link = $this->linkService->getLinksByParams(['short_url' => $shortLink])->first();

        if ($link) {
            $this->linkService->updateClicks((array) $link);

            return redirect()->to(url($link->original_url))->setStatusCode(201);
        }

        return redirect()->to(url('/'))->withErrors(['link' => 'Link is undefined']);
    }
}
