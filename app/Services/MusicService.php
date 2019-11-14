<?php

namespace App\Services;

use Guanguans\MusicPhp\MusicPhp;

class MusicService extends MusicPhp
{
    // protected $platforms = ['kugou'];
    protected $platforms = ['netease', 'xiami', 'kugou'];

    protected $hideFields = ['id', 'pic_id', 'url_id', 'lyric_id', 'url'];

    public function searchAll($keyword)
    {
        $songAll = [];

        foreach ($this->platforms as $platform) {
            $songAll = array_merge($songAll, $this->search($platform, $keyword));
        }

        return collect($songAll)
            ->map(function ($song) {
                return [
                    'title' => $song['name'],
                    'src' => $song['url'],
                    'artist' => implode('/', $song['artist']),
                ];
            })
            ->sortBy(function ($song) {
                return strlen($song['title'] . $song['artist']);
            })
            ->values()
            ->toArray();
    }

    public function search($platform, $keyword)
    {
        $meting = $this->getMeting($platform);

        try {
            $songs = json_decode($meting->format()->search($keyword, ['limit' => 5]), true);
        } catch (HttpException $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }

        foreach ($songs as $key => &$song) {
            try {
                $detail = json_decode($meting->format()->url($song['url_id']), true);
            } catch (HttpException $e) {
                throw new HttpException($e->getMessage(), $e->getCode(), $e);
            }

            if ($detail['url']) {
                $song = array_merge($song, $detail);
            } else {
                unset($songs[$key]);
            }
        }

        return $songs;
    }

    public function download(array $song)
    {
        try {
            $fileRealPath = $this->getDownloadsDir() . $song['name'] . '_' . implode(',', $song['artist']) . '.mp3';
            $this->getHttpClient()->get($song['url'], ['save_to' => $fileRealPath]);
            return $fileRealPath;
        } catch (Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
