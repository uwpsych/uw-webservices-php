<?php

namespace UwPsych\UwWebservices\Api;

class Entity extends AbstractApi
{
    use PersonApiTrait;

    /**
     * Get the entity by UW RegID.
     *
     * @link https://wiki.cac.washington.edu/display/pws/Entity+Resource+v2
     *
     * @param string $regid UW RegID.
     *
     * @return array information about the entity.
     */
    public function getByRegID($regid)
    {
        $regid = $this->validateID($regid, 'regid');

        return $this->getByID($regid);
    }

    /**
     * Get the entity by UW NetID.
     *
     * @link https://wiki.cac.washington.edu/display/pws/Entity+Resource+v2
     *
     * @param string $netid UW NetID.
     *
     * @return array Information about the entity.
     */
    public function getByNetID($netid)
    {
        $netid = $this->validateID($netid, 'netid');

        return $this->getByID($netid);
    }

    /**
     * Get the entity by UW RegID or UW NetID.
     *
     * @link https://wiki.cac.washington.edu/display/pws/Entity+Resource+v2
     *
     * @param string $id UW RegID or UW NetID.
     *
     * @return array Information about the entity.
     */
    protected function getByID($id)
    {
        $fragment = "entity/{$id}";
        $path = $this->buildPath($fragment);

        return $this->get($path);
    }

    /**
     * Search by Display Name, which is treated as a wildcard (i.e. "Psych" is treated as "Psych*")
     * You can filter by only_entities and is_test_entity.
     *
     * @link https://wiki.cac.washington.edu/pages/viewpage.action?spaceKey=pws&title=Person+Search+v2
     *
     * @param string $display_name The display name to search for.
     * @param boolean $only_entities If true, searches only for non-person entities.
     * @param boolean $is_test_entity If true, searches only for entities that are designated as "test entities".
     *
     * @return array List of entities found.
     */
    public function findByName($display_name, $only_entities = true, $is_test_entity = false)
    {
        $params = [];
        $params['display_name'] = $display_name;
        $params['only_entities'] = $only_entities ? 'on' : '';
        $params['is_test_entity'] = $is_test_entity ? 'on' : '';

        return $this->findBy($params);
    }

    protected function findBy($params)
    {
        $fragment = "entity";
        $path = $this->buildPath($fragment, $params);

        return $this->get($path);
    }
}
