<?php

namespace Redmine\Api;

/**
 * Listing issue categories, creating, editing.
 *
 * @link   http://www.redmine.org/projects/redmine/wiki/Rest_IssueCategories
 *
 * @author Kevin Saliou <kevin at saliou dot name>
 */
class IssueChecklist extends AbstractApi
{
    private $issueChecklists = array();

    /**
     * List issue categories.
     *
     * @link http://www.redmine.org/projects/redmine/wiki/Rest_IssueCategories#GET
     *
     * @param int $issue issue id
     * @param array      $params  optional parameters to be passed to the api (offset, limit, ...)
     *
     * @return array list of issue categories found
     */
    public function all($issue)
    {
        $this->issueChecklists = $this->retrieveAll('/issues/'.$issue.'/checklists.json', array());

        return $this->issueChecklists;
    }

    /**
     * Create a new issue category of $project given an array of $params.
     *
     * @link http://www.redmine.org/projects/redmine/wiki/Rest_IssueCategories#POST
     *
     * @param string|int $project project id or literal identifier
     * @param array      $params  the new issue category data
     *
     * @return SimpleXMLElement
     */
    public function create($issue, array $params = array())
    {
        $defaults = array(
            'subject' => null,
            'issue_id' => $issue,
            'is_done' => 0,
        );
        $params = $this->sanitizeParams($defaults, $params);

        if (
            !isset($params['subject'])
        ) {
            throw new \Exception('Missing mandatory parameters');
        }

        $xml = new SimpleXMLElement('<?xml version="1.0"?><checklist></checklist>');
        foreach ($params as $k => $v) {
            $xml->addChild($k, $v);
        }

        return $this->post('/issues/'.$issue.'/checklists.xml', $xml->asXML());
    }

    /**
     * Update issue category's information.
     *
     * @link http://www.redmine.org/projects/redmine/wiki/Rest_IssueCategories#PUT
     *
     * @param string $id     the issue category id
     * @param array  $params
     *
     * @return SimpleXMLElement
     */
    public function update($id, array $params)
    {
        $defaults = array(
            'subject' => null,
            'is_done' => null,
        );
        $params = $this->sanitizeParams($defaults, $params);

        $xml = new SimpleXMLElement('<?xml version="1.0"?><checklist></checklist>');
        foreach ($params as $k => $v) {
            $xml->addChild($k, $v);
        }

        return $this->put('/checklists/'.$id.'.xml', $xml->asXML());
    }
    
    public function remove($id)
    {
        return $this->delete('/checklists/'.$id.'.xml');
    }
}
