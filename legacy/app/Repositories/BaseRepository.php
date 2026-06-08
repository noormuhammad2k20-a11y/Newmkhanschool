<?php
namespace App\Repositories;

use App\Core\Model;

abstract class BaseRepository {
    protected string $modelClass;

    /**
     * Retrieve single record by ID.
     */
    public function find(int $id): ?Model {
        return ($this->modelClass)::find($id);
    }

    /**
     * Retrieve all records with paging.
     */
    public function all(int $limit = 100, int $offset = 0): array {
        return ($this->modelClass)::all($limit, $offset);
    }

    /**
     * Create a new model instance.
     */
    public function create(array $attributes): Model {
        $model = new $this->modelClass($attributes);
        $model->save();
        return $model;
    }

    /**
     * Update an existing model instance.
     */
    public function update(int $id, array $attributes): ?Model {
        $model = $this->find($id);
        if ($model) {
            foreach ($attributes as $key => $value) {
                $model->$key = $value;
            }
            $model->save();
        }
        return $model;
    }

    /**
     * Delete a model instance by ID.
     */
    public function delete(int $id): bool {
        $model = $this->find($id);
        return $model ? $model->delete() : false;
    }
}
