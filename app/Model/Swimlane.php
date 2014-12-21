<?php

namespace Model;

use SimpleValidator\Validator;
use SimpleValidator\Validators;

/**
 * Swimlanes
 *
 * @package  model
 * @author   Frederic Guillot
 */
class Swimlane extends Base
{
    /**
     * SQL table name
     *
     * @var string
     */
    const TABLE = 'swimlanes';

    /**
     * Value for active swimlanes
     *
     * @var integer
     */
    const ACTIVE = 1;

    /**
     * Value for inactive swimlanes
     *
     * @var integer
     */
    const INACTIVE = 0;

    /**
     * Get a swimlane by the id
     *
     * @access public
     * @param  integer   $swimlane_id    Swimlane id
     * @return array
     */
    public function getById($swimlane_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $swimlane_id)->findOne();
    }

    /**
     * Get all swimlanes for a given project
     *
     * @access public
     * @param  integer   $project_id    Project id
     * @return array
     */
    public function getAll($project_id)
    {
        return $this->db->table(self::TABLE)
                        ->eq('project_id', $project_id)
                        ->orderBy('position', 'asc')
                        ->findAll();
    }

    /**
     * Get active swimlanes
     *
     * @access public
     * @param  integer   $project_id    Project id
     * @return array
     */
    public function getSwimlanes($project_id)
    {
        $swimlanes = $this->db->table(self::TABLE)
                              ->columns('id', 'name')
                              ->eq('project_id', $project_id)
                              ->eq('is_active', self::ACTIVE)
                              ->orderBy('position', 'asc')
                              ->findAll();

        array_unshift($swimlanes, array('id' => 0, 'name' => t('Default')));

        return $swimlanes;
    }

    /**
     * Get the list of swimlanes
     *
     * @access public
     * @param  integer   $project_id    Project id
     * @return array
     */
    public function getList($project_id)
    {
        return $this->db->table(self::TABLE)
                        ->eq('project_id', $project_id)
                        ->asc('position')
                        ->listing('id', 'name');
    }

    public function create($project_id, $name)
    {
        return $this->persist(self::TABLE, array(
            'project_id' => $project_id,
            'name' => $name,
            'position' => $this->getLastPosition($project_id),
        ));
    }

    /**
     * Update a category
     *
     * @access public
     * @param  array    $values    Form values
     * @return bool
     */
    public function update(array $values)
    {
        return $this->db->table(self::TABLE)->eq('id', $values['id'])->save($values);
    }


    public function getLastPosition($project_id)
    {
        return $this->db->table(self::TABLE)->eq('project_id', $project_id)->count() + 1;
    }

    /**
     * Validate creation
     *
     * @access public
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateCreation(array $values)
    {
        $rules = array(
            new Validators\Required('project_id', t('The project id is required')),
            new Validators\Required('name', t('The name is required')),
        );

        $v = new Validator($values, array_merge($rules, $this->commonValidationRules()));

        return array(
            $v->execute(),
            $v->getErrors()
        );
    }

    /**
     * Validate modification
     *
     * @access public
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateModification(array $values)
    {
        $rules = array(
            new Validators\Required('id', t('The id is required')),
            new Validators\Required('name', t('The name is required')),
        );

        $v = new Validator($values, array_merge($rules, $this->commonValidationRules()));

        return array(
            $v->execute(),
            $v->getErrors()
        );
    }

    /**
     * Common validation rules
     *
     * @access private
     * @return array
     */
    private function commonValidationRules()
    {
        return array(
            new Validators\Integer('id', t('The id must be an integer')),
            new Validators\Integer('project_id', t('The project id must be an integer')),
            new Validators\MaxLength('name', t('The maximum length is %d characters', 50), 50)
        );
    }
}
