<?php

include_once (__DIR__ . '/DiscreteDistribution.php');

class WeightedUniform implements DiscreteDistribution {

    public $n = 1;
    public $domain;

    function __construct (array $weight = array (), array $domain = array ())
    {
        if (!empty ($domain))
        {
            $this->set_domain ($domain);
        }
    }


    function set_domain ($domain)
    {
        foreach ($domain as $case)
        {
            $this->n += $case->weight;
        }
        $this->domain = $domain;
    }


    public function p ($x)
    {
        return in_array ($x, $this->domain) ? 1 / ($this->n) : 0;
    }


    public function p_between ($x1, $x2)
    {
        
    }


    public function p_less ($x)
    {
        
    }


    public function p_more ($x)
    {
        
    }


    public function random ()
    {
        $rand = rand (0, $this->n - 1);
        $sum = 0;

        foreach ($this->domain as $case)
        {
            $sum += $case->weight;
            if ($sum >= $rand)
            {
                return $case;
            }
        }
    }


}
