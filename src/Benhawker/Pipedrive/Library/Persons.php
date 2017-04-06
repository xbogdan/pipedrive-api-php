<?php namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveMissingFieldError;

/**
 * Pipedrive Persons Methods
 *
 * Persons are your contacts, the customers you are doing Deals with.
 * Each Person can belong to an Organization.
 * Persons should not be confused with Users.
 *
 */
class Persons
{
    /**
     * Hold the pipedrive cURL session
     * @var \Benhawker\Pipedrive\Library\Curl Curl Object
     */
    protected $curl;

    /**
     * Initialise the object load master class
     */
    public function __construct(\Benhawker\Pipedrive\Pipedrive $master)
    {
        //associate curl class
        $this->curl = $master->curl();
    }

    /**
     * Returns all persons
     *
     * @param  array  search parameters
     * @return array returns detials of a person
     */
    public function getAll($param = array())
    {
        return $this->curl->get('persons/', $param);
    }

    /**
     * Returns a person
     *
     * @param  int   $id pipedrive persons id
     * @return array returns detials of a person
     */
    public function getById($id)
    {
        return $this->curl->get('persons/' . $id);
    }

    /**
     * Returns a person / people
     *
     * @param  string $name pipedrive persons name
     * @return array  returns detials of a person
     */
    public function getByName($name)
    {
        return $this->curl->get('persons/find', array('term' => $name));
    }

    /**
     * Returns a person / people with a specific email.
     *
     * @param  string $email pipedrive persons email
     * @return array  returns details of a person
     */
    public function getByEmail($email)
    {
        $person = array();
        $email_list = $this->curl->get('persons/find', array('term' => $email, 'search_by_email' => true));
        if (empty($email_list) || empty($email_list['data']))
            return $person;
        foreach ($email_list['data'] as $candidate) {
            if (strtolower($email) == strtolower($candidate['email']))
                return $candidate;
        }
        return $person;
    }

    /**
     * Returns a person / people based on a search term.
     *
     * @param  string $term term to search for
     * @return array  returns details of a person or more
     */
    public function search($term)
    {
        $person_list = $this->curl->get('searchResults', array('term' => $term, 'item_type' => 'person'));
        if (empty($person_list) || empty($person_list['data']))
            return array();
        return $person_list['data'];
    }

    /**
     * Lists deals associated with a person.
     *
     * @param  array $data (id, start, limit)
     * @return array deals
     */
    public function deals(array $data)
    {
        //if there is no id set throw error as it is a required field
        if (!isset($data['id'])) {
            throw new PipedriveMissingFieldError('You must include the "id" of the person when getting deals');
        }

        return $this->curl->get('persons/' . $data['id'] . '/deals');
    }

    /**
     * Lists products associated with a person.
     *
     * @param  array $data (id, start, limit)
     * @return array products
     */
    public function products(array $data)
    {
        //if there is no id set throw error as it is a required field
        if (!isset($data['id'])) {
            throw new PipedriveMissingFieldError('You must include the "id" of the person when getting products');
        }

        return $this->curl->get('persons/' . $data['id'] . '/products');
    }

    /**
     * Lists activities associated with a person.
     *
     * @param  array $data (id, start, limit)
     * @return array products
     */
    public function activities(array $data)
    {
        //if there is no id set throw error as it is a required field
        if (!isset($data['id'])) {
            throw new PipedriveMissingFieldError('You must include the "id" of the person when getting activities');
        }

        return $this->curl->get('persons/' . $data['id'] . '/activities');
    }

    /**
     * Updates a person
     *
     * @param  int   $personId pipedrives person Id
     * @param  array $data     new detials of person
     * @return array returns detials of a person
     */
    public function update($personId, array $data = array())
    {
        return $this->curl->put('persons/' . $personId, $data);
    }

    /**
     * Adds a person
     *
     * @param  array $data persons detials
     * @return array returns detials of a person
     */
    public function add(array $data)
    {
        //if there is no name set throw error as it is a required field
        if (!isset($data['name'])) {
            throw new PipedriveMissingFieldError('You must include a "name" field when inserting a person');
        }

        return $this->curl->post('persons', $data);
    }

    /**
     * Deletes a person
     *
     * @param  int   $personId pipedrives person Id
     * @return array returns detials of a person
     */
    public function delete($personId)
    {
        return $this->curl->delete('persons/' . $personId);
    }
}

