<?php

namespace Gedmo\Ipable;

/**
 * This interface is not necessary but can be implemented for
 * Entities which in some cases needs to be identified as
 * Ipable
 * 
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package Gedmo.Ipable
 * @subpackage Ipable
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface Ipable
{
    // ipable expects annotations on properties
    
    /**
     * @gedmo:Ipable(on="create")
     * dates which should be updated on insert only
     */
    
    /**
     * @gedmo:Ipable(on="update")
     * dates which should be updated on update and insert
     */
    
    /**
     * @gedmo:Ipable(on="change", field="field", value="value")
     * dates which should be updated on changed "property" 
     * value and become equal to given "value"
     */
    
    /**
     * example
     * 
     * @gedmo:Ipable(on="create")
     * @Column(type="date")
     * $created
     */
}