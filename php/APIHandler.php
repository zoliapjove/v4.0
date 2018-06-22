<?php
/**
 * @file        APIHandler.php
 * @brief       API Requests
 * @copyright   Copyright (C) GOautodial Inc.
 * @author      Alexander Jim Abenoja  <alex@goautodial.com>
 *
 * @par <b>License</b>:
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace creamy;

// dependencies
require_once('CRMDefaults.php');
require_once('LanguageHandler.php');
require_once('CRMUtils.php');
require_once('goCRMAPISettings.php');
require_once('Session.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


define("session_user", $_SESSION["user"]);
define("session_usergroup", $_SESSION["usergroup"]);
define("session_password", $_SESSION["phone_this"]);
/**
 *  APIHandler.
 *  This class is in charge of storing the API Connections for the basic functionality of the system.
 */
 class APIHandler {

	// language handler
	private $lh;

	/** Creation and class lifetime management */

	/**
     * Returns the singleton instance of UIHandler.
     * @staticvar APIHandler $instance The APIHandler instance of this class.
     * @return APIHandler The singleton instance.
     */
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }


    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    /*
     * Handles All API Requests
     * @param String $folder - Folder Name where API is located (ex. goUsers, goInbound, goVoicemails)
     * @param Array $postfields - Post Requests. API Name is required (ex. goAction => goGetUserGroupInfo, goAction => goGetAllUsers, goAction => goEditDID)
     * 
     * @return Array $output
    */
    public function API_Request($folder, $postfields){
		$url = gourl."/".$folder."/goAPI.php";

		$default_entries = array(
			'goUser' => session_user,
			'goPass' => session_password,
			'responsetype' => responsetype);

		$postdata = array_merge($default_entries, $postfields);

		// Call the API
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
		$data = curl_exec($ch);
		curl_close($ch);
	    $output = json_decode($data);
	    
		return $output;
	}

    public function API_getGOPackage(){
		$postfields = array(
			'goAction' => 'goGetPackage'
		);				

		return $this->API_Request("goPackages", $postfields);
	}

    public function API_goGetGroupPermission() {
		$postfields = array(
			'goAction' => 'goGetUserGroupInfo',
			'user_group' => session_usergroup,
			'session_user' => session_user
		);				

		return $this->API_Request("goUserGroups", $postfields);
	}

    public function goGetPermissions($type = 'dashboard') {
		
		$permissions = $this->API_goGetGroupPermission(session_usergroup);

		$decoded_permission = json_decode($permissions->data->permissions);
		
		$return = NULL;
		if (!empty($permissions)) {
			$types = explode(",", $type);
			if (count($types) > 1) {
				foreach ($types as $t) {
					if (array_key_exists($t, $decoded_permission)) {
						$return->{$t} = $decoded_permission->{$t};
					}
				}
			} else {
				if ($type == 'sidebar') {
					$return = $permissions;
				} else if (array_key_exists($type, $decoded_permission)) {
					$return = $decoded_permission->{$type};
				} else {
					$return = null;
				}
			}
		}

		return $return;
	}

	public function API_goGetAllUsers(){
		$postfields = array(
			'goAction' => 'goGetAllUsers',
			'session_user' => session_user
		);				

		return $this->API_Request("goUsers", $postfields);
	}

	// API to get usergroups
	public function API_goGetAllUserGroups() {
		$postfields = array(
			'goAction' => 'goGetAllUserGroups',
			'session_user' => session_user,
			'group_id' => session_usergroup
		);				

		return $this->API_Request("goUserGroups", $postfields);
	}

	public function API_getInGroups() {
		$postfields = array(
			'goAction' => 'goGetAllIngroup'
		);				

		return $this->API_Request("goInbound", $postfields);
	}

	public function API_modifyInGroups($postfields) {
		return $this->API_Request("goInbound", $postfields);
	}

	public function API_getInGroupInfo($groupid) {
		$postfields = array(
			'goAction' => 'goGetIngroupInfo',
			'group_id' => $groupid
		);				
		return $this->API_Request("goInbound", $postfields);
	}

	// Telephony IVR
	public function API_getIVR() {
		$postfields = array(
			'goAction' => 'goGetAllIVR'
		);

		return $this->API_Request("goInbound", $postfields);
	}

	public function API_modifyIVR($postfields) {
		return $this->API_Request("goInbound", $postfields);
	}

	//Telephony > phonenumber(DID)
	public function API_getPhoneNumber() {
		$postfields = array(
			'goAction' => 'goGetDIDsList'
		);				
		return $this->API_Request("goInbound", $postfields);
	}

	// Telephony Users -> Phone
	public function API_getAllPhones(){
		$postfields = array(
			'goAction' => 'goGetAllPhones',
			'session_user' => session_user
		);				
		return $this->API_Request("goPhones", $postfields);
	}

	/** Call Times API - Get all list of call times */
	public function API_getCalltimes(){
        $postfields = array(
			'goAction' => 'goGetAllCalltimes',
			'log_user' => session_user,
			'log_group' => session_usergroup,
			'log_ip' => $_SERVER['REMOTE_ADDR']
		);				
        return $this->API_Request("goCalltimes", $postfields);
	}

	// API Scripts
	public function API_getAllScripts(){
		$url = gourl."/goScripts/goAPI.php";
        $postfields = array(
			'goAction' => 'getAllScripts',
			'userid' => session_user
		);				
        return $this->API_Request("goScripts", $postfields);
	}

	// VoiceMails
	public function API_getAllVoiceMails() {
		$postfields = array(
			'goAction' => 'goGetAllVoicemails',
			'session_user' => session_user
		);				
		return $this->API_Request("goVoicemails", $postfields);
	}

	/** Voice Files API - Get all list of voice files */
	public function API_getAllVoiceFiles(){
		$postfields = array(
			'goAction' => 'goGetAllVoiceFiles',
			'session_user' => session_user
		);				
		return $this->API_Request("goVoiceFiles", $postfields);
	}

	/** Music On Hold API - Get all list of music on hold */
	public function API_getAllMusicOnHold(){
		$postfields = array(
			'goAction' => 'goGetAllMusicOnHold'
		);
		return $this->API_Request("goMusicOnHold", $postfields);
	}
}

?>