<?php
namespace App\Helpers\Axon\Search\Provider;

use Nomnom\Nomnom;
use App\Helpers\Axon\Search\Model\Torrent;
use App\Helpers\Axon\Search\Exception\UnexpectedResponseException;

/**
 * Search YIFY torrents
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class YifyProvider extends AbstractProvider
{
    /**
     * @var string
     */
    const DEFAULT_HOST = 'yts.am';

    /**
     * @var string
     */
    const DEFAULT_PATH = '/api/v2/list_movies.json';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'YIFY Torrents';
    }

    /**
     * {@inheritDoc}
     */
    public function getCanonicalName()
    {
        return 'yts';
    }

    /**
     * Generate the url for a search query
     *
     * @param string       $query
     * @param integer|null $page
     */
    public function getUrl($query, $page = null)
    {
        $url = sprintf(
            'https://%s%s?query_term=%s',
            self::DEFAULT_HOST,
            self::DEFAULT_PATH,
            rawurlencode($query)
        );

        if (is_integer($page)) {
            $url .= sprintf('&set=%d', $page);
        }

        return $url;
    }

    /**
     * @param string $rawResponse
     *
     * @return Torrent[]
     */
    protected function transformResponse($rawResponse)
    {
        if (!($stdClass = json_decode($rawResponse))) {
            throw new UnexpectedResponseException(
                'Could not parse response'
            );
        }

        if (isset($stdClass->data->movies)) {
            return array_map(function ($result) {
                $torrent = new Torrent();

                $torrent->setName($result->title ?? null);
                $torrent->setHash($result->torrents[0]->hash ?? null);

                preg_match('/([0-9\.]+) ([A-Za-z]+)/', $result->torrents[0]->size, $matches);
                $size = $matches[1] ?? 0;
                $unit = $matches[2] ?? 'B';

                $converter = new Nomnom($size);

                if ($unit == 'KB') {
                    $torrent->setSize($unit);
                } else {
                    $torrent->setSize($converter->from($unit)->to('B'));
                }

                $torrent->setSeeds($result->torrents[0]->seeds ?? null);
                $torrent->setPeers($result->torrents[0]->peers ?? null);
                $torrent->setLink($result->url ?? null);

                return $torrent;
            }, $stdClass->data->movies);
        }

        return [];
    }
}
