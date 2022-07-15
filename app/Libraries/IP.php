<?php

namespace App\Libraries;

class IP
{
    /**
     * Return the IPv4 or IPv6 adress of the client.
     *
     * @return  string
     */
    public function getIpAdress()
    {
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
			if (array_key_exists($key, $_SERVER) === true){
				foreach (explode(',', $_SERVER[$key]) as $ip){
					$ip = trim($ip); // just to be safe
					if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
						return $ip;
					}
				}
			}
		}

		return request()->ip();
    }

    /**
     * Return the filtered IPv4 or IPv6 adress of the client.
     *
     * @return  string
     */
    public function getFilteredIpAdress()
    {
        return $this->filterIpAdress($this->getIpAdress());
    }

    /**
     * Filter a IPv4 or IPv6 adress
     *
     * @param   string  $ip
     *
     * @return  string
     */
    public function filterIpAdress($ip_adress = null)
    {
		$ip_array = explode('.', $ip_adress);
		$new_ip = [];
		$i = 0;

		foreach($ip_array as $part) {
			if($part != $ip_array[0] && $part != end($ip_array)) {
				$new_ip[$i++] = str_repeat("*", strlen($part));
			} else {
				$new_ip[$i++] = $part;
			}
		}

		return implode('.', $new_ip);
    }
}
