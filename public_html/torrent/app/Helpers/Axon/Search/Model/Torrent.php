<?php
namespace App\Helpers\Axon\Search\Model;

/**
 * Represents a search result for torrents
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class Torrent
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var integer
     */
    protected $size;

    /**
     * @var integer
     */
    protected $seeds;

    /**
     * @var integer
     */
    protected $peers;

    /**
     * @var string
     */
    protected $link;

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = trim((string) $name);
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = strtoupper((string) $hash);
    }

    /**
     * @return string|null
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param integer $size
     */
    public function setSize($size)
    {
        $this->size = (integer) $size;
    }

    /**
     * @return integer|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param integer $seeds
     */
    public function setSeeds($seeds)
    {
        $this->seeds = (integer) $seeds;
    }

    /**
     * @return integer|null
     */
    public function getSeeds()
    {
        return $this->seeds;
    }

    /**
     * @param integer $peers
     */
    public function setPeers($peers)
    {
        $this->peers = (integer) $peers;
    }

    /**
     * @return integer
     */
    public function getPeers()
    {
        return $this->peers;
    }

    /**
     * @return string|null $link
     */
    public function setLink($link)
    {
        $this->link = (string) $link;
    }

    /**
     * @return string|null
     */
    public function getLink()
    {
        return $this->link;
    }

    public static function convertSize($bytes, $force_unit = NULL, $format = NULL, $si = TRUE)
    {
        $format = ($format === NULL) ? '%01.2f %s' : (string) $format;

        // IEC prefixes (binary)
        if ($si == FALSE OR strpos($force_unit, 'i') !== FALSE){
            $units = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
            $mod   = 1024;
        } else {
            $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
            $mod   = 1000;
        }

        if (($power = array_search((string) $force_unit, $units)) === FALSE) {
            $power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
        }

        return sprintf($format, $bytes / pow($mod, $power), $units[$power]);
    }


}
