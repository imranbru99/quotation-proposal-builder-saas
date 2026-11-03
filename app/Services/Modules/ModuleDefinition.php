<?php

namespace App\Services\Modules;

class ModuleDefinition
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $category = 'Operations',
        public string $icon = 'heroicon-o-cube',
        public string $version = '1.0.0',
        public bool $isCore = false,
        public array $dependencies = [],
        public array $features = [],
        public array $defaultSettings = []
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'icon' => $this->icon,
            'version' => $this->version,
            'is_core' => $this->isCore,
            'dependencies' => $this->dependencies,
            'features' => $this->features,
            'default_settings' => $this->defaultSettings,
        ];
    }
}

