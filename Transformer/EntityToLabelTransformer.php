<?php
namespace DynamicFormBundle\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\PropertyAccess\PropertyAccess;

class EntityToLabelTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;
    /**
     * @var string
     */
    protected $class;
    protected $property;

    public function __construct(ObjectManager $objectManager, $class, $property)
    {
        $this->objectManager = $objectManager;
        $this->class = $class;
        $this->property = $property;
    }

    public function transform($entity)
    {
        if ($entity === null) {
            return null;
        }
        $propertyAccess = PropertyAccess::createPropertyAccessor();
        return $propertyAccess->getValue($entity, $this->property);
    }

    public function reverseTransform($id)
    {

    }
}