<?php

namespace App\Traits;

use LogicException;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

trait SyncFiles
{
    public function syncFiles(): self
    {
        return $this->dissociateFiles()->associateFiles();
    }

    public function dissociateFiles(): self
    {
        $methodName = $this->guessFileRelationName();

        $this->$methodName()->update(
            [
                'entity_id' => null,
                'entity_type' => null,
            ]
        );

        return $this;
    }

    public function associateFiles(): self
    {
        $this->getAllFiles()
            ->each(function ($file) {
                $file->entity()->associate($this);
                $file->save();
            });

        return $this;
    }

    /**
     * Override this method
     *
     * @return Collection
     */
    public function getAllFiles(): Collection
    {
        return collect();
    }

    /**
     * Guess the "morph" relationship name between this model and file model
     *
     * @return Relation
     */
    public function guessFileRelationName(): string
    {
        $names = ['files', 'file'];

        foreach ($names as $name) {
            if (method_exists($this, $name)) {
                return $name;
            }
        }

        throw new LogicException('SyncFiles@guessFileRelationName - No available methods');
    }
}
