<?php

namespace UwPsych\UwWebservices\Api;

class Entity extends AbstractApi
{
    use PersonApiTrait;

    /**
     * Get the entity by UW RegID.
     *
     * @link https://wiki.cac.washington.edu/display/pws/Entity+Resource+v2
     */
    public function getByRegID(string $regid): array
    {
        $regid = $this->validateID($regid, 'regid');

        return $this->getByID($regid);
    }

    /**
     * Get the entity by UW NetID.
     *
     * @link https://wiki.cac.washington.edu/display/pws/Entity+Resource+v2
     */
    public function getByNetID(string $netid): array
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
     */
    protected function getByID(string $id): array
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
     * @param string $displayName The display name to search for.
     * @param bool $onlyEntities If true, searches only for non-person entities.
     * @param bool $isTestEntity If true, searches only for entities that are designated as "test entities".
     */
    public function findByName(string $displayName, bool $onlyEntities = true, bool $isTestEntity = false): array
    {
        $params = [];
        $params['display_name'] = $displayName;
        $params['only_entities'] = $onlyEntities ? 'on' : '';
        $params['is_test_entity'] = $isTestEntity ? 'on' : '';

        return $this->findBy($params);
    }

    protected function findBy(array $params): array
    {
        $fragment = "entity";
        $path = $this->buildPath($fragment, $params);

        return $this->get($path);
    }
}
