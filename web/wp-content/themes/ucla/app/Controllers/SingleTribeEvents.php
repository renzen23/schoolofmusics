<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class SingleTribeEvents extends Controller
{   
    protected $template = 'single-tribe_events';
    protected $acf = true;

    public function isEventProgram()
    {
        $program = get_query_var('program');

        return $program; 
    }

    public function eventLayout()
    {
        $program = $this->isEventProgram();

        if( $program == 1 ) {
            return 'app-program';
        } else {
            return 'app';
        }
    }
}