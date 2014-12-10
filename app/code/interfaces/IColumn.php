<?php

namespace App\Code\Interfaces;

interface IColumn
{
    public function getName();

    public function setName($value);

    public function setRequired($value);

    public function isRequired();

    public function getType();

    public function setType($value);

    public function setValue($value);

    public function getValue();

    public function getDefault();

    public function setDefault($value);

    public function restore();

    public function getPrevious();
} 