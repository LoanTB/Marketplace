<?php
namespace App\Ecommerce\Modele\DataObject;

abstract class AbstractDataObject {
    public abstract function formatTableau(bool $nulls = true): array;
}