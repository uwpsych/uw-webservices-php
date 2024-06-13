<?php

namespace UwPsych\UwWebservices\Api;

class Person extends AbstractApi
{
    use PersonApiTrait;

    /**
     * Get the person by UW RegID.
     *
     * @link https://wiki.cac.washington.edu/display/pws/Person+v2
     */
    public function getByRegID(string $regid): array
    {
        $regid = $this->validateID($regid, 'regid');

        return $this->getByID($regid);
    }

    /**
     * Get the person by UW NetID.
     *
     * @link https://wiki.cac.washington.edu/display/pws/Person+v2
     */
    public function getByNetID(string $netid): array
    {
        $netid = $this->validateID($netid, 'netid');

        return $this->getByID($netid);
    }

    /**
     * Get the person by UW RegID or UW NetID.
     *
     * @link https://wiki.cac.washington.edu/display/pws/Person+v2
     *
     * @param string $id UW RegID or UW NetID.
     */
    protected function getByID(string $id): array
    {
        $fragment = 'person/' . $id;
        // add full to fragment if specified
        $fragment .= $this->options['full'] ? '/full' : '';

        $path = $this->buildPath($fragment);

        return $this->get($path);
    }

    /**
     * Find people by UW Regid(s).
     *
     * @link https://wiki.cac.washington.edu/pages/viewpage.action?spaceKey=pws&title=Person+Search+v2
     *
     * @param string|array $regids Either a single UW Regid as a string or an array of Regids.
     */
    public function findByRegid(string|array $regids, array $params = []): array
    {
        $params['uwregid'] = $this->validateIDs($regids, 'regid');

        return $this->findBy($params);
    }

    /**
     * Find people by UW NetID(s).
     *
     * @link https://wiki.cac.washington.edu/pages/viewpage.action?spaceKey=pws&title=Person+Search+v2
     *
     * @param string|array $netids Either a single UW NetID as a string or an array of NetIDs.
     */
    public function findByNetID(string|array $netids, array $params = []): array
    {
        $params['uwnetid'] = $this->validateIDs($netids, 'netid');

        return $this->findBy($params);
    }

    /**
     * Find people by UW Employee ID(s).
     *
     * @link https://wiki.cac.washington.edu/pages/viewpage.action?spaceKey=pws&title=Person+Search+v2
     *
     * @param string|array $employeeIds either a single UW Employee ID as a string or an array of Employee IDs.
     */
    public function findByEmployeeID(string|array $employeeIds, array $params = []): array
    {
        $params['employee_id'] = $this->validateIDs($employeeIds, 'employee_id');

        return $this->findBy($params);
    }

    /**
     * Find people by UW Student Number(s).
     *
     * @link https://wiki.cac.washington.edu/pages/viewpage.action?spaceKey=pws&title=Person+Search+v2
     *
     * @param string|array $studentNumbers either a single UW Student Number as a string or an array of Student Numbers.
     */
    public function findByStudentNumber(string|array $studentNumbers, array $params = []): array
    {
        $params['student_number'] = $this->validateIDs($studentNumbers, 'student_number');

        return $this->findBy($params);
    }

    /**
     * Search by Last Name, First Name (note: also searches Middle Name), or both.
     * Wildcards can be specified with an asterisk.
     * You can also filter by UW affiliations.
     *
     * @link https://wiki.cac.washington.edu/pages/viewpage.action?spaceKey=pws&title=Person+Search+v2
     *
     * @param array $names List of first_name, last_name, or both.
     * @param array $affiliations List of UW affiliations to filter on. Can be any of student, staff, faculty, employee, member, alum, affiliate.
     */
    public function findByName(array $names, array $affiliations = [], array $params = []): array
    {
        // TODO: consider validating names
        $params['first_name'] = $names['first_name'] ?? null;
        $params['last_name'] = $names['last_name'] ?? null;

        $params = $this->processAffiliations($affiliations, $params);

        return $this->findBy($params);
    }

    /**
     * Search by Department, Home Department, or both.
     * Wildcards can be specified with an asterisk.
     * You can also filter by UW affiliations.
     *
     * @link https://wiki.cac.washington.edu/pages/viewpage.action?spaceKey=pws&title=Person+Search+v2
     *
     * @param array $department Array with keys of 'department' and/or 'home_dept' used to search for users.
     * @param array $affiliations List of UW affiliations to filter on. Can be any of student, staff, faculty, employee, member, alum, affiliate.
     */
    public function findByDepartment(array $department, array $affiliations = [], array $params = []): array
    {
        $params['department'] = $department['department'] ?? null;
        $params['home_dept'] = $department['home_department'] ?? null;

        $params = $this->processAffiliations($affiliations, $params);

        return $this->findBy($params);
    }

    protected function findBy(array $params): mixed
    {
        // split array values for id fields
        $idFields = ['uwregid', 'uwnetid', 'employee_id', 'student_number', 'student_system_key', 'development_id'];
        foreach ($params as $key => $value) {
            if (in_array($key, $idFields)) {
                $params[$key] = is_array($value) ? implode(',', $value) : $value;
            }
        }

        $fragment = "person";
        $path = $this->buildPath($fragment, $params);

        return $this->get($path);
    }

    protected function processAffiliations(array $affiliations, array $params): array
    {
        // Add affiliations to params
        $validAffiliations = ['student', 'staff', 'faculty', 'employee', 'member', 'alum', 'affiliate'];
        foreach ($affiliations as $affiliation) {
            if (in_array($affiliation, $validAffiliations)) {
                $key = "edupersonaffiliation_{$affiliation}";
                $params[$key] = 'true';
            }
        }

        return $params;
    }
}
