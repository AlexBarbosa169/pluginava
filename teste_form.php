<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of teste_form
 *
 * @author alexs
 */
 
class teste_form extends moodleform{
    //put your code here
    protected function definition() {         
        $mform = $this->_form; // Don't forget the underscore!  
        $mform->addElement('button', 'intro', get_string("buttonlabel"));
    }        
}
