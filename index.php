<?

include (__DIR__ . '/Flask.php');

// Flask::setup ($name);


$my_ab_test = Flask::create_test ('my-ab-test')->enable(true)
                    ->add_case ('X', [
                        'default' => true
                    ])
                    ->add_case ('Y', [
                        'weight' => 10
                    ])
                    ->add_case ('Z', [
                        
                    ])
                    ->create_job ('get_class', function($case){
                        return 'ab-test-class-' . $case;
                    })
                    ->with_probability_distribution(Flask::DISTRO_UNIFORM);

for ($i = 0; $i < 100; $i++)
{
    echo Flask::sample_once ('my-ab-test')->get_class() . '<br>';
}
//
//Flask::application ('my-ab-testing')
//        ->create_categories ('A', array (
//            'class' => 'ab-case-a'
//        ))
//        ->create_categories ('B', array (
//            'class' => 'ab-case-b'
//        ))
//        ->create_categories ('C', array (
//            'class' => 'ab-case-b'
//        ))
//        ->with_probability_distribution (new WeightedUniform (array (
//            'A' => 1,
//            'B' => 2,
//            'C' => 1
//        )));


