<?php

namespace Getnet\API;

trait TraitEntity
{
    public function jsonSerialize(): mixed
    {
        $entity = clone $this;

        if (method_exists($entity, 'beforeSerialize')) {
            $entity->beforeSerialize();
        }

        return $entity->toArray();
    }

    public function toArray(): array
    {
        $vars = get_object_vars($this);

        if ($this->hiddenNullValues()) {
            return array_filter($vars, function ($value) {
                return null !== $value;
            });
        }

        return $vars;
    }

    public function toJSON($hiddenNull = true): string|false
    {
        if ($hiddenNull) {
            return json_encode($this);
        }

        return json_encode(get_object_vars($this));
    }

    public function populateByArray(array $body, array $blockFields = [])
    {
        foreach ($body as $prop => $value) {
            if (property_exists($this, $prop) && null !== $value && !in_array($prop, $blockFields)) {
                $this->{$prop} = $value;
            }
        }

        return $this;
    }

    private function hiddenNullValues(): bool
    {
        return true;
    }
}
