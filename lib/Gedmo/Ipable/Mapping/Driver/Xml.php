<?php

namespace Gedmo\Ipable\Mapping\Driver;

use Gedmo\Mapping\Driver\Xml as BaseXml,
    Doctrine\Common\Persistence\Mapping\ClassMetadata,
    Gedmo\Exception\InvalidMappingException;

/**
 * This is a xml mapping driver for Ipable
 * behavioral extension. Used for extraction of extended
 * metadata from xml specificaly for Ipable
 * extension.
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @author Miha Vrhovnik <miha.vrhovnik@gmail.com>
 * @package Gedmo.Ipable.Mapping.Driver
 * @subpackage Xml
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Xml extends BaseXml
{

    /**
     * List of types which are valid for IP
     *
     * @var array
     */
    private $validTypes = array(
        'string'
    );

    /**
     * {@inheritDoc}
     */
    public function validateFullMetadata(ClassMetadata $meta, array $config) {}

    /**
     * {@inheritDoc}
     */
    public function readExtendedMetadata(ClassMetadata $meta, array &$config)
    {
        /**
         * @var \SimpleXmlElement $mapping
         */
        $mapping = $this->_getMapping($meta->name);

        if (isset($mapping->field)) {
            /**
             * @var \SimpleXmlElement $fieldMapping
             */
            foreach ($mapping->field as $fieldMapping) {
                $fieldMappingDoctrine = $fieldMapping;
                $fieldMapping = $fieldMapping->children(self::GEDMO_NAMESPACE_URI);
                if (isset($fieldMapping->ipable)) {
                    /**
                     * @var \SimpleXmlElement $data
                     */
                    $data = $fieldMapping->ipable;

                    $field = $this->_getAttribute($fieldMappingDoctrine, 'name');
                    if (!$this->isValidField($meta, $field)) {
                        throw new InvalidMappingException("Field - [{$field}] type is not valid and must be 'string' in class - {$meta->name}");
                    }
                    if (!$this->_isAttributeSet($data, 'on') || !in_array($this->_getAttribute($data, 'on'), array('update', 'create', 'change'))) {
                        throw new InvalidMappingException("Field - [{$field}] trigger 'on' is not one of [update, create, change] in class - {$meta->name}");
                    }

                    if ($this->_getAttribute($data, 'on') == 'change') {
                        if (!$this->_isAttributeSet($data, 'field') || !$this->_isAttributeSet($data, 'value')) {
                            throw new InvalidMappingException("Missing parameters on property - {$field}, field and value must be set on [change] trigger in class - {$meta->name}");
                        }
                        $field = array(
                            'field' => $field,
                            'trackedField' => $this->_getAttribute($data, 'field'),
                            'value' => $this->_getAttribute($data, 'value')
                        );
                    }
                    $config[$this->_getAttribute($data, 'on')][] = $field;
                }
            }
        }
    }

    /**
     * Checks if $field type is valid
     *
     * @param ClassMetadata $meta
     * @param string $field
     * @return boolean
     */
    protected function isValidField(ClassMetadata $meta, $field)
    {
        $mapping = $meta->getFieldMapping($field);
        return $mapping && in_array($mapping['type'], $this->validTypes);
    }
}
