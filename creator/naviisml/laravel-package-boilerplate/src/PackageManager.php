<?php

namespace Naviisml\Package;

class PackageManager
{
    /**
     * @return mixed
     */
    public function handle()
    {
        return $this->driver($driver);
    }
}
