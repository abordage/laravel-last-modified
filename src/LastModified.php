<?php

namespace Abordage\LastModified;

use Illuminate\Support\Carbon;

class LastModified
{
    private ?Carbon $updatedAt = null;

    public function set(?Carbon $updated_at): void
    {
        $this->updatedAt = $updated_at;
    }

    public function get(): ?Carbon
    {
        return $this->updatedAt;
    }
}
