<?php
namespace App\Helpers\Axon\Search\Provider;

use Nomnom\Nomnom;
use Symfony\Component\DomCrawler\Crawler;
use App\Helpers\Axon\Search\Model\Torrent;

/**
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class KickassProvider extends AbstractProvider
{
    /**
     * @var string
     */
    const DEFAULT_HOST = 'kickass2.ch';

    /**
     * @var string
     */
    const DEFAULT_PATH = '/usearch';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Kickass';
    }

    /**
     * {@inheritDoc}
     */
    public function getCanonicalName()
    {
        return 'kickass';
    }

    /**
     * @param string       $query
     * @param integer|null $page
     *
     * @return string
     */
    public function getUrl($query, $page)
    {
        if (is_integer($page)) {
            return sprintf(
                'http://%s%s/%s/%s/?field=seeders&order=desc',
                self::DEFAULT_HOST,
                self::DEFAULT_PATH,
                $query,
                $page
            );
        } else {
            return sprintf(
                'http://%s%s/%s/?field=seeders&order=desc',
                self::DEFAULT_HOST,
                self::DEFAULT_PATH,
                $query
            );
        }
    }

    /**
     * @param string $rawResponse
     *
     * @return Torrent[]
     */
    protected function transformResponse($rawResponse)
    {
        try {
            $crawler = new Crawler($rawResponse);

            $data = $crawler->filter('tr[id^="torrent_latest_torrents"]')->each(function ($node) {

                $magnet = $node->filter('a.cellMainLink')->attr('href');

                $hash = rand(1000, 10534535435345450);

                $sizeElement = $node->filter('td.nobr')->text();
                preg_match('/([0-9\.]+)/', $sizeElement, $size);
                preg_match('/([A-Za-z]+)/', $sizeElement, $unit);

                $converter = new Nomnom($size[0]);
                $torrent = new Torrent();

                $torrent->setName($node->filter('a.cellMainLink')->text());
                $torrent->setHash($hash);

                if ($unit[0] == 'KB') {
                    $torrent->setSize($unit[0]);
                } else {
                    $torrent->setSize($converter->from($unit[0])->to('B'));
                }
                
                $torrent->setSeeds($node->filter('td.green')->text());
                $torrent->setPeers($node->filter('td.red')->text());
                $torrent->setLink('http://' . self::DEFAULT_HOST .$node->filter('a.cellMainLink')->attr('href'));

                return $torrent;
            });

            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }
}
