<?php

namespace Codenexus\GeoIP;

use GeoIp2\Database\Reader;

class GeoIP {
	/**
	 * Client IP address.
	 * 
	 * @var float
	 */
	protected $client_ip;

	/**
	 * Create a new GeoIP instance
	 */
	public function __construct()
	{
		$this->client_ip = $this->getClientIP();
	}

	/**
	 * Retrieve location data from database
	 * 
	 * @param  float $ip
	 * 
	 * @return object
	 */
	public function getLocation($ip = null)
	{
		// If no IP given then get client IP 
		if (! $ip) {
			$ip = $this->getClientIp();
		}

		// Check IP to make sure it is valid
		if (! $this->checkIp($ip))
		{
			throw new \Exception("IP Address is either not a valid IPv4/IPv6 address or falls within the private or reserved ranges");
		}

		$reader = new Reader(storage_path('app/geoip.mmdb'));
		$record = $reader->city($ip);

		return $record;
	}

    /**
     * Checks IP to make sure it is a valid IPv4 or IPv6 address and
     * not within a private or reserved range
     * 
     * @param  float $ip
     * 
     * @return float|bool
     */
    public function checkIp($ip)
    {
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    /**
     * Gets the client IP address.
     *
     * @return float
     */
    private function getClientIp()
    {
    	if (getenv('HTTP_CLIENT_IP')) {
        	$ipaddress = getenv('HTTP_CLIENT_IP');
    	} elseif(getenv('HTTP_X_FORWARDED_FOR')) {
        	$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    	} elseif(getenv('HTTP_X_FORWARDED')) {
        	$ipaddress = getenv('HTTP_X_FORWARDED');
    	} elseif(getenv('HTTP_FORWARDED_FOR')) {
        	$ipaddress = getenv('HTTP_FORWARDED_FOR');
    	} elseif(getenv('HTTP_FORWARDED')) {
        	$ipaddress = getenv('HTTP_FORWARDED');
    	} elseif(getenv('REMOTE_ADDR')) {
        	$ipaddress = getenv('REMOTE_ADDR');
   		} else {
   			$ipaddress = '127.0.0.1';
   		}
 
    	return $ipaddress;
    }

    /**
     * Return client_ip.
     *
     * @return float
     */
    public function getClientIp()
    {
    	return $this->client_ip;
    }
}