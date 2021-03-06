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
 * Formulaire de configuration minimal de discom viewer
 *
 * @package     mod_dicomviewer
 * @category    admin
 * @license     GNU General Public License
 * @copyright   2021 | Stage DUT AS Informatique
 */

namespace mod_dicomviewer\privacy;

use core_privacy\local\metadata\null_provider;

/**
 * this class precises that no personal data is storred by the plugin.
 */
class provider implements null_provider {

    /**
     * Récupère l'identifiant de la chaîne de langue avec le fichier de langue
     * du composant pour expliquer pourquoi ce plugin ne stocke aucune donnée.
     *
     * @return string
     */
    public static function  get_reason () : string {
        return  'privacy:metadata';
    }
}
