<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */


/**
 * Holds a few more specialized configurations
 * @package Hazelcast\Config
 */
class Config {
    /**
     * @var GroupConfig
     */
    protected $groupConfig;

    /**
     * @var CacheConfig
     */
    protected $cacheConfig;

    /**
     * @var SerializerConfig
     */
    protected $serializerConfig;

    /**
     * @var NetworkConfigStore
     */
    protected $networkConfigStore;

    /**
     * Init sub configs
     */
    public function __construct()
    {
        $this->groupConfig = new GroupConfig();
        $this->cacheConfig = new CacheConfig();
        $this->serializerConfig = new SerializerConfig($this->getCacheConfig());
        $this->networkConfigStore = new NetworkConfigStore();
    }

    /**
     * @return GroupConfig
     */
    public function getGroupConfig()
    {
        return $this->groupConfig;
    }

    /**
     * @return CacheConfig
     */
    public function getCacheConfig()
    {
        return $this->cacheConfig;
    }

    /**
     * @return SerializerConfig
     */
    public function getSerializerConfig()
    {
        return $this->serializerConfig;
    }

    /**
     * @return NetworkConfigStore
     */
    public function getNetworkConfigStore()
    {
        return $this->networkConfigStore;
    }
}
