<?php
declare(strict_types=1);

namespace SwaggerCustomTest\App\Model\Entity;

use Cake\ORM\Entity;
use SwaggerCustom\Lib\Annotation\SwagEntity;

/**
 * DepartmentEmployee Entity
 * @SwagEntity(isPublic=false)
 * @property int $employee_id
 * @property int $department_id
 * @property \Cake\I18n\FrozenDate $from_date
 * @property \Cake\I18n\FrozenDate $to_date
 * @property int $id
 *
 * @property \SwaggerCustom\Test\Model\Entity\Employee $employee
 * @property \SwaggerCustom\Test\Model\Entity\Department $department
 */
class DepartmentEmployee extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'employee_id' => true,
        'department_id' => true,
        'from_date' => true,
        'to_date' => true,
        'employee' => true,
        'department' => true,
    ];
}
