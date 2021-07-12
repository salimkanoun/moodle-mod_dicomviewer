<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Classe formulaire de config.
 * 
 * @package     mod_dicomviewer
 * @category    admin
 * @license     GNU General Public License
 * @copyright   2021 | Stage DUT AS Informatique
 */

defined('MOODLE_INTERNAL') || die();

//Moodleform (bibliotheque formulaire).
require_once("$CFG->libdir/formslib.php");

/**
 * Form for the settings of distribution static viewer ohif and stone.
 */
class form_config extends moodleform{

    /**
     * Add elements to the form.
     */
    public function definition(){
	    global $CFG;
 		
        //Recuperation des champs de configuration.
        $dataJsonStone = json_decode(file_get_contents('viewer-stone/configuration.json'), true);
        $dataJsonOhif = json_decode(file_get_contents('viewer-ohif/configuration.json'), true);

        $mform = $this->_form; //Instancie un formulaire.

        //Taille des champs de saisie.
        $attributs = array('size' => '80');

        $mform->addElement('header', 'headerstone', get_string('titlestone', 'dicomviewer'));

        addelementclassiconform($mform, 'title_expected', 'stone_expectedorigin', $dataJsonStone['StoneWebViewer']['ExpectedMessageOrigin'], $attributs);
        addelementclassiconform($mform, 'title_dicomweb', 'stone_dicomwebroot', $dataJsonStone['StoneWebViewer']['DicomWebRoot'], $attributs);

        $mform->addElement('header', 'headerohif', get_string('titleohif', 'dicomviewer'));

        addelementclassiconform($mform, 'title_wadoUriRoot', 'ohif_wadoUriRoot', $dataJsonOhif['servers']['dicomWeb'][0]['wadoUriRoot'], $attributs);
        addelementclassiconform($mform, 'title_qidoRoot', 'ohif_qidoRoot', $dataJsonOhif['servers']['dicomWeb'][0]['qidoRoot'], $attributs);
        addelementclassiconform($mform, 'title_wadoRoot', 'ohif_wadoRoot', $dataJsonOhif['servers']['dicomWeb'][0]['wadoRoot'], $attributs);

        $this->add_action_buttons();

	}

    /**
     * Custom validation added.
     * Update config files of viewer-ohif and stone-ohif.
     * 
     * @param array $data form data
     * @param object $files 
     * @return array 
     */
	function validation($data, $files) {

        $arrayEmpty = false;
        foreach($data as $value){
            if(empty($value)){
                $arrayEmpty = true;
            }
        }

        if(!$arrayEmpty){
            //Remplacement dans le fichier de configuration des viewer.

            //Modifier le fichier configuration.json du viewer-ohif.
            $datajsonohif = json_decode(file_get_contents('viewer-ohif/configuration.json'), true);
            $datajsonohif['servers']['dicomWeb'][0]['wadoUriRoot'] = $data['ohif_wadoUriRoot'];
            $datajsonohif['servers']['dicomWeb'][0]['qidoRoot'] = $data['ohif_qidoRoot'];
            $datajsonohif['servers']['dicomWeb'][0]['wadoRoot'] = $data['ohif_wadoRoot'];

            file_put_contents("viewer-ohif/configuration.json", json_encode($datajsonohif)); 

            //Ecriture du fichier configuration.json de stone.
            $datajsonstone = json_decode(file_get_contents('viewer-stone/configuration.json'), true);
            $datajsonstone['StoneWebViewer']['ExpectedMessageOrigin'] = $data['stone_expectedorigin'];
            $datajsonstone['StoneWebViewer']['DicomWebRoot'] = $data['stone_dicomwebroot'];
            file_put_contents("viewer-stone/configuration.json", json_encode($datajsonstone)); 
        }
    
        return array();
    }

}

/**
 * Function for add an element on the form.
 * @param object $mform formulaire to add element.
 * @param string $stringtitle title of the element.
 * @param string $stringname name of the element.
 * @param string $defaultvalue value of the element.
 * @param array $attributs default of the element.
 * @return true Validate the element
 * 
 */
function addelementclassiconform($mform, $stringtitle, $stringname, $defaultvalue, $attributs){

    // Ajour élément dans le formulaire.
    $mform->addElement('text', $stringname, get_string($stringtitle, 'dicomviewer'), $attributs);
    //Définit le type de l'élement.
    $mform->setType($stringname, PARAM_TEXT);   
    //Element a coté du bouton help, string dans lang du titre et de help, fichier du lang.
    $mform->addHelpButton($stringname, $string_title, 'dicomviewer');
    //Valeur par défaut.
    $mform->setDefault($stringname, $defaultvalue);
    //Element a coté, string de l'erreur, le type du role, reinitialiser a sa valeur origine ?, false.
    $mform->addRule($stringname, get_string('invalid_param', 'dicomviewer'), 'required', true, false);

    return true;
}