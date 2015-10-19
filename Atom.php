<?php

class Atom {

    public $name;
    public $weight;
    public $class;
    public $jobs = array ();

    public function __construct ($name)
    {
        $this->name = $name;
    }


    public function set_job ($jobs)
    {
        $this->jobs = $jobs;
        return $this;
    }


    public function __call ($name, $arguments)
    {
        if (array_key_exists ($name, $this->jobs))
        {
            return call_user_func_array ($this->jobs[$name], array_merge(array ($this->name), $arguments));
        }
        else
        {
            return false;
        }
    }


}
