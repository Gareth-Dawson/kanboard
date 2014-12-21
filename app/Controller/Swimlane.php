<?php

namespace Controller;

/**
 * Swimlanes
 *
 * @package controller
 * @author  Frederic Guillot
 */
class Swimlane extends Base
{
    /**
     * Get the swimlane (common method between actions)
     *
     * @access private
     * @param  integer    $project_id
     * @return array
     */
    private function getSwimlane($project_id)
    {
        $swimlane = $this->swimlane->getById($this->request->getIntegerParam('swimlane_id'));

        if (! $swimlane) {
            $this->session->flashError(t('Swimlane not found.'));
            $this->response->redirect('?controller=swimlane&action=index&project_id='.$project_id);
        }

        return $swimlane;
    }

    /**
     * List of swimlanes for a given project
     *
     * @access public
     */
    public function index(array $values = array(), array $errors = array())
    {
        $project = $this->getProjectManagement();

        $this->response->html($this->projectLayout('swimlane/index', array(
            'swimlanes' => $this->swimlane->getList($project['id']),
            'values' => $values + array('project_id' => $project['id']),
            'errors' => $errors,
            'project' => $project,
            'title' => t('Swimlanes')
        )));
    }

    /**
     * Validate and save a new swimlane
     *
     * @access public
     */
    public function save()
    {
        $project = $this->getProjectManagement();

        $values = $this->request->getValues();
        list($valid, $errors) = $this->swimlane->validateCreation($values);

        if ($valid) {

            if ($this->swimlane->create($project['id'], $values['name'])) {
                $this->session->flash(t('Your swimlane have been created successfully.'));
                $this->response->redirect('?controller=swimlane&action=index&project_id='.$project['id']);
            }
            else {
                $this->session->flashError(t('Unable to create your swimlane.'));
            }
        }

        $this->index($values, $errors);
    }

    /**
     * Edit a swimlane (display the form)
     *
     * @access public
     */
    public function edit(array $values = array(), array $errors = array())
    {
        $project = $this->getProjectManagement();
        $swimlane = $this->getSwimlane($project['id']);

        $this->response->html($this->projectLayout('swimlane/edit', array(
            'values' => empty($values) ? $swimlane : $values,
            'errors' => $errors,
            'project' => $project,
            'title' => t('Swimlanes')
        )));
    }

    /**
     * Edit a swimlane (validate the form and update the database)
     *
     * @access public
     */
    public function update()
    {
        $project = $this->getProjectManagement();

        $values = $this->request->getValues();
        list($valid, $errors) = $this->swimlane->validateModification($values);

        if ($valid) {

            if ($this->swimlane->update($values)) {
                $this->session->flash(t('Your swimlane have been updated successfully.'));
                $this->response->redirect('?controller=swimlane&action=index&project_id='.$project['id']);
            }
            else {
                $this->session->flashError(t('Unable to update your swimlane.'));
            }
        }

        $this->edit($values, $errors);
    }

    /**
     * Confirmation dialog before removing a swimlane
     *
     * @access public
     */
    public function confirm()
    {
        $project = $this->getProjectManagement();
        $swimlane = $this->getSwimlane($project['id']);

        $this->response->html($this->projectLayout('swimlane/remove', array(
            'project' => $project,
            'swimlane' => $swimlane,
            'title' => t('Remove a swimlane')
        )));
    }

    /**
     * Remove a swimlane
     *
     * @access public
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $project = $this->getProjectManagement();
        $swimlane = $this->getSwimlane($project['id']);

        if ($this->swimlane->remove($swimlane['id'])) {
            $this->session->flash(t('Swimlane removed successfully.'));
        } else {
            $this->session->flashError(t('Unable to remove this swimlane.'));
        }

        $this->response->redirect('?controller=swimlane&action=index&project_id='.$project['id']);
    }
}
