<?php
namespace App\Helpers\Axon\Search\Provider;

use App\Helpers\Axon\Search\Model\Torrent;

/**
 * The interface search providers should implement
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
interface ProviderInterface
{
    /**
     * Get the name of the provider
     *
     * @return string
     */
    public function getName();

    /**
     * Get the canonical name of the provider
     *
     * @return string
     */
    public function getCanonicalName();

    /**
     * Perform a search query on the provider for the specific query and page
     *
     * @param string       $query
     * @param integer|null $page
     *
     * @return Torrent[]
     */
    public function search($query, $page = null);
}
