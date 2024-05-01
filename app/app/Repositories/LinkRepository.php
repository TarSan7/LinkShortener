<?php

namespace App\Repositories;

use App\Models\Link;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LinkRepository
{
    /**
     * @param string $link
     * @param string $shortedLink
     * @param int $clicks
     * @return Link
     */
    public function save(string $link, string $shortedLink, int $clicks): Link
    {
        return Link::create([
            'original_url' => $link,
            'short_url'    => $shortedLink,
            'clicks'       => $clicks
        ]);
    }

    /**
     * @param array $params
     * @return Collection
     */
    public function getByParams(array $params): Collection
    {
        $query = DB::table('links');

        foreach ($params as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get();
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Link::all();
    }

    /**
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update(int $id, array $params): bool
    {
        return DB::table('links')
            ->where('id', $id)
            ->update($params);
    }
}
