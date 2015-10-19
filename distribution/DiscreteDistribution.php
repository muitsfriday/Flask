<?php

interface DiscreteDistribution {
    
    function p($x);
    function p_less($x);
    function p_more($x);
    function p_between($x1, $x2); 
    function random ();
    function set_domain($domain);
}
