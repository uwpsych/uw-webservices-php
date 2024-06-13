<?php

namespace UwPsych\UwWebservices\Api;

class Person extends AbstractApi
{
    use PersonApiTrait;

    /**
     * Get the person by UW RegID.
     *
     * @link https://wiki.cac.washington.edu/display/pws/Person+v2
     *
     * @param string $regid UW RegID.
     *
     * @return array information about the person.
     */
    public function getByRegID($regid)
    {
        $regid = $this->validateID($regid, 'regid');

        return $this->getByID($regid);
    }

    /**
     * Get the person by UW NetID.
     *
     * @link https://wiki.cac.washington.edu/display/pws/Person+v2
     *
     * @param string $netid UW NetID.
     *
     * @return array Information about the person.
     */
    public function getByNetID($netid)
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
     *
     * @return array Information about the person.
     */
    protected function getByID($id)
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
     *
     * @return array List of people found.
     */
    public function findByRegid($regids, $params = [])
    {
        $regids = $this->validateIDs($regids, 'regid');
        $params['uwregid'] = $regids;

        return $this->findBy($params);
    }

    /**
     * Find people by UW NetID(s).
     *
     * @link https://wiki.cac.washington.edu/pages/viewpage.action?spaceKey=pws&title=Person+Search+v2
     *
     * @param string|array $netids Either a single UW NetID as a string or an array of NetIDs.
     *
     * @return array List of people found.
     */
    public function findByNetID($netids, $params = [])
    {
        $netids = $this->validateIDs($netids, 'netid');
        $params['uwnetid'] = $netids;

        return $this->findBy($params);
    }

    /**
     * Find people by UW Employee ID(s).
     *
     * @link https://wiki.cac.washington.edu/pages/viewpage.action?spaceKey=pws&title=Person+Search+v2
     *
     * @param string|array $employee_ids either a single UW Employee ID as a string or an array of Employee IDs.
     *
     * @return array List of people found.
     */
    public function findByEmployeeID($employee_ids, $params = [])
    {
        $employee_ids = $this->validateIDs($employee_ids, 'employee_id');
        $params['employee_id'] = $employee_ids;

        return $this->findBy($params);
    }

    /**
     * Find people by UW Student Number(s).
     *
     * @link https://wiki.cac.washington.edu/pages/viewpage.action?spaceKey=pws&title=Person+Search+v2
     *
     * @param string|array $student_numbers either a single UW Student Number as a string or an array of Student Numbers.
     *
     * @return array List of people found.
     */
    public function findByStudentNumber($student_numbers, $params = [])
    {
        $student_numbers = $this->validateIDs($student_numbers, 'student_number');
        $params['student_number'] = $student_numbers;

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
     *
     * @return array List of people found.
     */
    public function findByName($names, $affiliations = [], $params = [])
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
     *
     * @return array List of people found.
     */
    public function findByDepartment($department, $affiliations = [], $params = [])
    {
        $params['department'] = $department['department'] ?? null;
        $params['home_dept'] = $department['home_department'] ?? null;

        $params = $this->processAffiliations($affiliations, $params);

        return $this->findBy($params);
    }

    protected function findBy($params)
    {
        // split array values for id fields
        $id_fields = ['uwregid', 'uwnetid', 'employee_id', 'student_number', 'student_system_key', 'development_id'];
        foreach ($params as $key => $value) {
            if (in_array($key, $id_fields)) {
                $params[$key] = is_array($value) ? implode(',', $value) : $value;
            }
        }

        $fragment = "person";
        $path = $this->buildPath($fragment, $params);

        return $this->get($path);
    }

    protected function processAffiliations($affiliations, $params)
    {
        // Add affiliations to params
        $valid_affiliations = ['student', 'staff', 'faculty', 'employee', 'member', 'alum', 'affiliate'];
        foreach ($affiliations as $affiliation) {
            if (in_array($affiliation, $valid_affiliations)) {
                $key = "edupersonaffiliation_{$affiliation}";
                $params[$key] = 'true';
            }
        }

        return $params;
    }
}
