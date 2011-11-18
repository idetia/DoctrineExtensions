<?php

namespace Gedmo\Ipable\Mapping\Event\Adapter;

use Gedmo\Mapping\Event\Adapter\ODM as BaseAdapterODM;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Gedmo\Ipable\Mapping\Event\IpableAdapter;

/**
 * Doctrine event adapter for ODM adapted
 * for Ipable behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package Gedmo\Ipable\Mapping\Event\Adapter
 * @subpackage ODM
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ODM extends BaseAdapterODM implements IpableAdapter
{
    /**
     * {@inheritDoc}
     */
    public function getIPValue(ClassMetadata $meta, $field)
    {
        $ip = false; // No IP found

        /**
         * User is behind a proxy and check that we discard RFC1918 IP addresses.
         * If these address are behind a proxy then only figure out which IP belongs
         * to the user.
         */
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']); // Put the IP's into an array which we shall work with.
            $no = count($ips);
            for ($i = 0 ; $i < $no ; $i++) {

                /**
                 * Skip RFC 1918 IP's 10.0.0.0/8, 172.16.0.0/12 and
                 * 192.168.0.0/16
                 */
                if (!eregi('^(10|172\.16|192\.168)\.', $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }

            }

        }
        return ($ip ? $ip : isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1'); // Return with the found IP, the remote address or the local loopback address
        
    }
}