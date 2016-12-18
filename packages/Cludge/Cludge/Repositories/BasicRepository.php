<?php

namespace App\Repositories;

use Cludge\Contracts\RepositoryInterface;
use App\Exceptions\RepositoryNameException;
use App\Exceptions\RepositoryModelException;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;

abstract class BasicRepository implements RepositoryInterface {

    /**
     * The instance of the App
     *
     * @var App
     */
    private $app;

    /**
     * Any boolean values that need to set
     *
     * @var array
     */
    protected $booleans = [];

    /**
     * Any optional fields that need to be filtered
     *
     * @var array
     */
    protected $optional = [];

    /**
     * Any values that are nullable, so null should be set if they are empty
     *
     * @var array
     */
    protected $nullable = [];

    /**
     * The instance of the model
     *
     * @var mixed
     */
    protected $model;

    /**
     * Fields that should be filtered from the input array
     *
     * @var array
     */
    protected $except = ['_method', '_token'];

    /**
     * Constructor
     *
     * @param App $app The instnace of the app
     *
     * @return void
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Get the name of the model
     *
     * @return string
     */
    protected function model()
    {
        $class = get_class($this);
        $parts = explode('\\', $class);
        $name = last($parts);

        // Check the file name for auto generation of model name
        if ( ! preg_match('/Repository$/', $name)) {
            throw new RepositoryNameException('The repository class name does not follow the "Model" "Repository" naming pattern, overrite the model method to set the model name');
        }

        return preg_replace('/Repository$/', '', $name);
    }

    /**
     * Make an instance of the model from the DI
     *
     * @return mixed
     */
    protected function makeModel()
    {
        $model = $this->app->make('\App\Models\\'.$this->model());

        if ( ! $model instanceof Model) {
            throw new RepositoryModelException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }


    /**
     * Fetch the results
     *
     * @param Eloquent\Model &$query  The eloquent model
     * @param string         $method  The method to use
     * @param array          $options The options
     * @param array          $params  The params array
     *
     * @return Eloquent\Collection
     */
    protected function fetch(&$query, $method, $options, $params = [])
    {
        $this->orderBy($query, $options);

        // If the query should be returned for manual modification
        if (isset($options['execute']) && $options['execute'] === false) {
            return $query;
        }

        // If an alter method has been implemented, then
        $this->alter($query);

        // Run an appropriate method
        switch ($method) {
            case 'find':
            case 'findOrFail':
                return $query->{$method}($params['id'], $params['columns']);
                break;
            case 'paginate':
                return $query->paginate($params['perPage'], $params['columns']);
                break;
            default:
                return $query->{$method}($params['columns']);
        }
    }

    /**
     * Get a query with all the set scopes and withs
     *
     * @param  array  $options The options
     *
     * @return Eloquent\Model
     */
    public function query($options = [])
    {
        $query = $this->model->newQuery();

        // Add any with statements
        if ( ! empty($options['with']) && is_array($options['with'])) {
            $query->with($options['with']);
        }

        // Add all the scopes to the query
        if ( ! empty($options['scopes']) && is_array($options['scopes'])) {
            $this->addScopes($query, $options['scopes']);
        }

        // Filter the query
        if ( ! isset($options['filter']) || $options['filter'] === true) {
            $this->filter($query);
        }

        $this->scopes($query);

        return $query;
    }

    /**
     * Add all the scopes to the query
     * If there was an array for the scope, the value will be used as options for the scope
     *
     * @param Eloquent\Model &$query The
     * @param array          $scopes The array of scopes
     *
     * @return void
     */
    protected function addScopes(&$query, $scopes = [])
    {
        foreach ($scopes as $key => $scope) {
            if (is_array($scope)) {
                $query->{$key}($scope);
            } else {
                $query->{$scope}();
            }
        }
    }

    /**
     * Order the query by passed in parameters
     *
     * @param Eloquet\Query &$query  The query
     * @param array         $options The options
     *
     * @return void
     */
    protected function orderBy(&$query, $options)
    {
        if ( ! empty($options['orderBy']) && is_array($options['orderBy'])) {
            $orders = $options['orderBy'];

            // So we can loop through all the orders
            if ( ! is_array(reset($orders))) {
                $orders = [$orders];
            }

            foreach ($orders as $order) {
                list($field, $direction) = $order;
                $query->orderBy($field, $direction);
            }
        }
    }


    /*
    |---------------------------------------------------------------------
    | Optional methods for the concrete class to implement
    |---------------------------------------------------------------------
    |
    */


    /**
     * Alter the query
     *
     * @param  Eloquent\Query &$query The query
     *
     * @return void
     */
    protected function alter(&$query)
    {
        // Concrete class can alter the query by implmenting this method
    }

    /**
     * Stub for filter method
     *
     * @param Eloquent\Query &$query The query
     *
     * @return void
     */
    protected function filter(&$query)
    {
        // Concrete class can implement a global filter
        // this can be skipped with params 'filter' => false
    }


    /**
     * Stub for scopes method
     *
     * @param Eloquent\Query &$query The query
     *
     * @return void
     */
    protected function scopes(&$query)
    {
        // Concrete class can implement a scopes filter
        // This is in addition of the filters
    }


    /**
     * All the entities
     *
     * @param array  $options The options array
     * @param array  $columns The columns to fetch
     *
     * @return void
     */
    public function all($options = [], $columns = ['*'])
    {
        $query = $this->query($options);

        return $this->fetch($query, 'get', $options, ['columns' => $columns]);
    }

    /**
     * Get the paginated results
     *
     * @param integer $perPage The number per page
     * @param array   $options The options array
     * @param array   $columns The columns to fetch
     *
     * @return mixed
     */
    public function paginate($perPage = 15, $options = [], $columns = ['*'])
    {
        $query = $this->query($options);

        return $this->fetch($query, 'paginate', $options, ['perPage' => 10, 'columns' => $columns]);
    }


    /**
     * Find by id
     * @param integer $id      The id of the entity
     * @param array   $options The options array
     * @param array   $columns The columns to fetch
     *
     * @return mixed
     */
    public function find($id, $options = [], $columns = ['*'])
    {
        $query = $this->query($options);

        return $this->fetch($query, 'find', $options, ['id' => $id, 'columns' => $columns]);
    }


    /**
     * Find or fail
     *
     * @param integer $id      The id of the entity
     * @param array   $options The options array
     * @param array   $columns The columns to fetch
     *
     * @return mixed
     */
    public function findOrFail($id, $options = [], $columns = ['*'])
    {
        $query = $this->query($options);

        return $this->fetch($query, 'findOrFail', $options, ['id' => $id, 'columns' => $columns]);
    }


    /**
     * Find by an attribute
     *
     * @param string $attribute The field
     * @param mixed  $value     The value
     * @param array  $options   The options array
     * @param array  $columns   The columns to fetch
     *
     * @return mixed
     */
    public function findBy($attribute, $value, $options = [], $columns = ['*'])
    {
        $query = $this->query($options);

        $query->where($attribute, '=', $value);

        return $this->fetch($query, 'first', $options, ['columns' => $columns]);
    }

    /**
     * Create an entity
     *
     * @param array $data The data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update an entity
     *
     * @param array  $data      The data to update
     * @param mixed  $id        The value of the attribute
     * @param string $attribute The attribute
     *
     * @return mixed
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }


    /**
     * Delete an entity
     *
     * @param mixed $ids The id or ids of the entity
     *
     * @return boolean
     */
    public function delete($ids) {

        return $this->model->destroy($ids);
    }


    /**
     * Get the input values
     *
     * @param Illuminate\Request &$request The request object
     * @param array              $options  The options array
     *
     * @return array
     */
    protected function getInputData(&$request, $options)
    {
        // Return the specified values
        if ( ! empty($options['only']) && is_array($options['only'])) {
            return $request->only($options['only']);
        } else {
            // Filter out the except values
            $except = $this->except;
            if ( ! empty($options['except'])) {
                $except = array_merge($except, $options['except']);
            }

            return $request->except($except);
        }
    }


    /**
     * Save
     *
     * @param Illuminate\Request &$request  The request object
     * @param mixed              $entity_id The entity id if update
     * @param array              $options   The options
     *
     * @return mixed
     */
    public function save(&$request, $entity_id = null, $options = [])
    {
        $data = $this->getInputData($request, $options);

        return $this->saveWithData($data, $entity_id, $options);
    }


    /**
     * Save the entity with a passed in data array
     *
     * @param array $data      The data array
     * @param mixed $entity_id The entity id if it exists
     * @param array $options   The options array
     *
     * @return mixed
     */
    public function saveWithData($data, $entity_id = null, $options = [])
    {
        // Get the entity
        $entity = null;
        if ( ! empty($entity_id)) {
            if (is_object($entity_id)) {
                $entity =& $entity_id;
            } else {
                $entity = $this->find($entity_id, $options);
            }
        }

        // Set a new flag
        $data['new_entity'] = empty($entity);

        // Keep a clone of the old one
        $old = (empty($entity)) ? null : clone $entity;

        if ( ! empty($this->booleans)) $data = $this->addBooleans($data, $options);
        if ( ! empty($this->optional)) $data = $this->removeOptional($data, $options);
        if ( ! empty($this->nullable)) $data = $this->setNullable($data, $entity);

        // Save the entity
        if ( ! $entity) {
            $entity = $this->create($data);
        } else {
            $entity->update($data);
        }

        // If we have a need to compare the two objects
        if (method_exists($this, 'compare')) $this->compare($entity, $old);

        return $entity;
    }


    /**
     * Add booleans to the input array
     *
     * @param array $data     The data array
     * @param array $options  The options array
     *
     * @return array
     */
    protected function addBooleans($data, $options = [])
    {
        foreach ($this->booleans as $boolean) {
            if ( ! isset($data[$boolean])) {
                $data[$boolean] = false;
            }
        }

        return $data;
    }

    /**
     * Remove any optional field
     *
     * @param array $data The data array
     *
     * @return array
     */
    protected function removeOptional($data)
    {
        foreach ($this->optional as $option) {
            if (empty($data[$option])) {
                unset($data[$option]);
            }
        }

        return $data;
    }

    /**
     * Set null on nullable field
     *
     * @param array $data The data array
     *
     * @return array
     */
    protected function setNullable($data)
    {
        foreach ($this->nullable as $option) {
            if (empty($data[$option])) {
                $data[$option] = null;
            }
        }

        return $data;
    }

}