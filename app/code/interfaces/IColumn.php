<?php

namespace App\Code\Interfaces;


interface IColumn
{
    public function getName();
    public function setName();
    public function isRequired();
    public function getType();
    public function setType();
    public function setValue();
    public function getValue();
    public function getDefault();
    public function setDefault();
    public function restore();
} 