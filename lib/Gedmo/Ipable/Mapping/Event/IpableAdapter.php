<?php

namespace Gedmo\Ipable\Mapping\Event;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Gedmo\Mapping\Event\AdapterInterface;

/**
 * Doctrine event adapter interface
 * for Ipable behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package Gedmo\Ipable\Mapping\Event
 * @subpackage IpableAdapter
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface IpableAdapter extends AdapterInterface
{
    /**
     * Get the date value
     *
     * @param ClassMetadata $meta
     * @param string $field
     * @return mixed
     */
    function getIPValue(ClassMetadata $meta, $field);
}