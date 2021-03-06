<?php
/**
 * This file is part of the "Lost and Found" project
 *
 * @copyright Stfalcon.com <info@stfalcon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\DBAL\Types\ItemAreaTypeType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * ItemAreaValidator
 *
 * @author Artem Genvald  <genvaldartem@gmail.com>
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class ItemAreaValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($item, Constraint $constraint)
    {
        if ($item->getAreaType()) {
            switch ($item->getAreaType()) {
                case ItemAreaTypeType::MARKER:
                    if (!$item->getLatitude() || !$item->getLongitude()) {
                        $this->buildMessage(ItemAreaTypeType::MARKER, $constraint);
                    }
                    break;
                default:
                    if (!$item->getArea()) {
                        $this->buildMessage($item->getAreaType(), $constraint);
                    }
                    break;
            }
        } else {
            $this->buildMessage('figure', $constraint);
        }
    }

    /**
     * Build message
     *
     * @param string     $replacedMessage Replaced message
     * @param Constraint $constraint      Constraint
     */
    private function buildMessage($replacedMessage, Constraint $constraint)
    {
        return $this->context
                    ->buildViolation($constraint->message)
                    ->setParameter('%areaType%', $replacedMessage)
                    ->addViolation();
    }
}
