<?php

include_once (__DIR__ . '/Atom.php');
include_once (__DIR__ . '/distribution/Uniform.php');
include_once (__DIR__ . '/distribution/WeightedUniform.php');

class Flask {

    const DISTRO_UNIFORM = 1;
    const DISTRO_WEIGHTED_UNIFORM = 2;

    public static $application_tag = 'ab-test';
    public static $instance = array ();
    public $atom_collection = array ();
    public $is_enable = true;

    public static function get_instance ($key)
    {
        return !isset (Flask::$instance[$key]) ? Flask::$instance[$key] = new Flask ($key) : Flask::$instance[$key];
    }


    public function create_test ($name)
    {
        return Flask::get_instance ($name);
    }


    public function add_case ($case_name, $options = array ())
    {
        $this->atom_collection[$case_name] = new Atom ($case_name);

        if (!empty ($options['default']))
        {
            $this->default_case = $this->atom_collection[$case_name];
        }

        if (!empty ($options['weight']))
        {
            $this->atom_collection[$case_name]->weight = $options['weight'];
        }
        else
        {
            $this->atom_collection[$case_name]->weight = 1;
        }

        return $this;
    }


    public function create_job ($job_name, $function)
    {
        $this->jobs[$job_name] = $function;

        return $this;
    }


    public function with_probability_distribution ($distribution_function)
    {
        if (is_int ($distribution_function))
        {
            if ($distribution_function == Flask::DISTRO_UNIFORM)
            {
                $distribution_function = new Uniform ();
            }
            else if ($distribution_function == Flask::DISTRO_WEIGHTED_UNIFORM)
            {
                $distribution_function = new WeightedUniform ();
            }
            $distribution_function->set_domain ($this->atom_collection);
            $this->distribution = $distribution_function;
        }
        return $this;
    }


    public function enable ($flag)
    {
        $this->is_enable = $flag;

        return $this;
    }


    public function sample ($name)
    {
        if (!isset (Flask::$instance[$name]))
        {
            return false;
        }

        if (Flask::$instance[$name]->is_enable == false)
        {
            return Flask::$instance[$name]->default_case->set_job (Flask::$instance[$name]->jobs);
        }

        return Flask::$instance[$name]->distribution->random ()->set_job (Flask::$instance[$name]->jobs);
    }


    public function sample_once ($name)
    {
        if (!isset (Flask::$instance[$name]))
        {
            return false;
        }

        if (Flask::$instance[$name]->is_enable == false)
        {
            return Flask::$instance[$name]->default_case->set_job (Flask::$instance[$name]->jobs);
        }

        if (!isset (Flask::$instance[$name]->sample_one))
        {
            Flask::$instance[$name]->sample_one = Flask::$instance[$name]->distribution->random ();
        }

        return Flask::$instance[$name]->sample_one->set_job (Flask::$instance[$name]->jobs);
    }


    public static function __callStatic ($name, $arguments)
    {
        $arg_num = count ($arguments);

        if ($arg_num == 1)
        {
            $this->instance->$name ($arguments[0]);
        }
        elseif ($arg_num == 2)
        {
            $this->instance->$name ($arguments[0], $arguments[1]);
        }
        elseif ($arg_num == 3)
        {
            $this->instance->$name ($arguments[0], $arguments[1], $arguments[3]);
        }
        elseif ($arg_num == 4)
        {
            $this->instance->$name ($arguments[0], $arguments[1], $arguments[3]);
        }

        call_user_method_array ($name, $this->instance, $params);
    }


}
