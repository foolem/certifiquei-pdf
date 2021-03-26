<?php

namespace App;

interface StorageInterface {
    public function getFileUrl(string $fileName): string;
    public function getBasePath(): string;
}
