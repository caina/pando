<?php

class Analytics {
	
	 var $ci;
	 var $options;
	 var $user_model;
	 var $config;
	 var $data;

	 public function __construct($config){
		$this->ci =&get_instance();
		$this->config = $config;
	}

	public function show($options = array(),$data){
		
		return "";
	}

	public function save_action($field=""){
		return "";
	}

	public function ajax_save(){
	}

	public function list_act(){

	}

	public function generate(){
		return "";
	}

	public function set_options($options){

	}

	private function get_options(){
		
	}


	public function dashboard_display(){
		$this->ci->load->model("user_model");
		if(empty($this->ci->user_model->user->profile_id)){
			return;
		}
		@session_start();

		require_once APPPATH.'third_party/Google_api/src/Google/autoload.php';

		$client_id = '121864429952-2fb1efk8v9rhq46iud08hvg2sdcer3uu.apps.googleusercontent.com'; //Client ID
		$service_account_name = '121864429952-2fb1efk8v9rhq46iud08hvg2sdcer3uu@developer.gserviceaccount.com'; //Email Address 
		$key_file_location = APPPATH.'third_party/Google_api/src/Google/Pando-8c981025c45b.p12'; //key.p12

		$client = new Google_Client();
		$client->setApplicationName("ApplicationName");
		$service = new Google_Service_Analytics($client);

		if (isset($_SESSION['service_token'])) {
		  $client->setAccessToken($_SESSION['service_token']);
		}

		$key = file_get_contents($key_file_location);
		$cred = new Google_Auth_AssertionCredentials(
		    $service_account_name,
		    array(
		        'https://www.googleapis.com/auth/analytics',
		    ),
		    $key,
		    'notasecret'
		);
		$client->setAssertionCredentials($cred);
		if($client->getAuth()->isAccessTokenExpired()) {
		  $client->getAuth()->refreshTokenWithAssertion($cred);
		}
		$_SESSION['service_token'] = $client->getAccessToken();
		

		$analytics = new Google_Service_Analytics($client);

		$profileId = "ga:95600714";
		$profileId = "ga:".$this->ci->user_model->user->profile_id;
		// die($profileId);
		$startDate = date('Y-m-d', strtotime('-31 days')); // 31 days from now
		$endDate = date('Y-m-d'); // todays date

		$metrics = "ga:sessions,ga:newUsers,ga:users,ga:percentNewSessions,ga:timeOnPage,ga:exitRate,ga:hits";
		// $metrics = "ga:dataSource";

		$optParams = array("dimensions" => "ga:date");
		try {
			$results = $analytics->data_ga->get($profileId, $startDate, $endDate, $metrics, $optParams);
			
		} catch (Exception $e) {
			return;
		}

		$data_analytics = new stdClass();
		$data_analytics->total_access = $results->totalsForAllResults["ga:sessions"];
		$data_analytics->new_users_percentage = $results->totalsForAllResults["ga:percentNewSessions"];
		$data_analytics->time_on_page = gmdate("H:i:s",$results->totalsForAllResults["ga:timeOnPage"]);
		$data_analytics->exit_rate = $results->totalsForAllResults["ga:exitRate"];
		$data_analytics->hits = $results->totalsForAllResults["ga:hits"];

		$data_analytics->rows = $results->rows;

		foreach ($data_analytics->rows as &$row) {
			$row["day"] = date("d",strtotime($row[0]));
			$row["month"] = date("m",strtotime($row[0]));
			$row["year"] = date("Y",strtotime($row[0]));
		}
		// dump($data_analytics);
		
		$data['report'] = $data_analytics; 
		return $this->ci->load->view('libraries_view/new_analytics',$data,true);
	}


		// 	ga:hits

		// UI Name: Hits
		// Total number of hits sent to Google Analytics. This metric sums all hit types (e.g. pageview, event, timing, etc.).

		// Attributes:

	public function dashboard_display_(){
		$this->data["analytics"] = false;
		$this->ci->load->model("user_model");
		$this->data["logged_user"] = $this->ci->user_model->is_user_logged();
		// dump($this->data["logged_user"]);die;
		//  quando tiver internet de novo pode descomentar
		// if(1!=1){
		if(!empty($this->data["logged_user"]->profile_id)){

			// dump($this->data["logged_user"]);
			$this->ci->load->library('ga_api');


			$this_month =  date("Y-m-t") ;
			$last_month = date("Y-m-1", strtotime("-1 month") ) ;

			// TODO verifica isso aqui, não sei se esta certo
			// if(!$this->ci->ga_api->login()) return;


			$this->ga = $this->ci->ga_api->login()->init(array('profile_id' => $this->data["logged_user"]->profile_id));
			$this->data["analytics"] = $this->ci->ga_api->login()
			    ->dimension('ga:month,ga:day')
			    ->metric('ga:newUsers,ga:users,ga:percentNewSessions,ga:timeOnPage,ga:exitRate')
			    ->sort_by('ga:month,ga:day',true)
			    ->when($last_month,$this_month)
			    ->limit(60)
			    ->get_object();

			  $arr = array();
			  $arr[] = array("Data", "Acessos este mês", "Acessos mês anterior");
			  
			  $month_data=array();
			  $elements = $this->data["analytics"];
			  unset($elements["summary"]);
			  $current_month_data = $elements[date("m")];
			  unset($elements[date("m")]);
			  $last_month_data = (array) current($elements);

		  		foreach ($current_month_data as $day => $data) {
		  			//usando o mes como chave, ele bugava no 10, muito estranho
					$last_month_usage = 0;
					foreach($last_month_data as $key => $ele){
						if($day==$key){
							$last_month_usage = $ele->users;
						}
					}
		  			$arr[]= array("{$day}/".date("m"), (float) $data->users, (float) $last_month_usage);
		  		}

			  $this->data["analytics_json"] = json_encode($arr);

			  return $this->ci->load->view('libraries_view/analytics',$this->data,true);
		}

		  // print(json_encode($arr));die;
		    // dump($this->data["analytics"]);
		    // die;


		return "";
	}
	



	function getService()
	{
	  // Creates and returns the Analytics service object.

	  // Load the Google API PHP Client Library.
	  // require_once 'google-api-php-client/src/Google/autoload.php';
	  require_once APPPATH.'third_party/Google_api/src/Google/autoload.php';

	    $client = new Google_Client();
	  $client->setApplicationName("contato@apanda.com.br");
	  $client->setDeveloperKey("AIzaSyBX98zkxCCkQcxS_-qfKftDKgAgYPgS078");

	  

	  // Create and configure a new client object.
	  $client = new Google_Client();
	  $client->setApplicationName("HelloAnalytics");
	  $analytics = new Google_Service_Analytics($client);

	  // // Read the generated client_secrets.p12 key.
	  // $key = file_get_contents($key_file_location);
	  // $cred = new Google_Auth_AssertionCredentials(
	  //     $service_account_email,
	  //     array(Google_Service_Analytics::ANALYTICS_READONLY),
	  //     $key
	  // );
	  // $client->setAssertionCredentials($cred);
	  // if($client->getAuth()->isAccessTokenExpired()) {
	  //   $client->getAuth()->refreshTokenWithAssertion($cred);
	  // }

	  return $analytics;
	}

	function getFirstprofileId(&$analytics) {
	  // Get the user's first view (profile) ID.

	  // Get the list of accounts for the authorized user.
	  $accounts = $analytics->management_accounts->listManagementAccounts();

	  if (count($accounts->getItems()) > 0) {
	    $items = $accounts->getItems();
	    $firstAccountId = $items[0]->getId();

	    // Get the list of properties for the authorized user.
	    $properties = $analytics->management_webproperties
	        ->listManagementWebproperties($firstAccountId);

	    if (count($properties->getItems()) > 0) {
	      $items = $properties->getItems();
	      $firstPropertyId = $items[0]->getId();

	      // Get the list of views (profiles) for the authorized user.
	      $profiles = $analytics->management_profiles
	          ->listManagementProfiles($firstAccountId, $firstPropertyId);

	      if (count($profiles->getItems()) > 0) {
	        $items = $profiles->getItems();

	        // Return the first view (profile) ID.
	        return $items[0]->getId();

	      } else {
	        throw new Exception('No views (profiles) found for this user.');
	      }
	    } else {
	      throw new Exception('No properties found for this user.');
	    }
	  } else {
	    throw new Exception('No accounts found for this user.');
	  }
	}

	function getResults(&$analytics, $profileId) {
	  // Calls the Core Reporting API and queries for the number of sessions
	  // for the last seven days.
	   return $analytics->data_ga->get(
	       'ga:' . $profileId,
	       '7daysAgo',
	       'today',
	       'ga:sessions');
	}

	function printResults(&$results) {
	  // Parses the response from the Core Reporting API and prints
	  // the profile name and total sessions.
	  if (count($results->getRows()) > 0) {

	    // Get the profile name.
	    $profileName = $results->getProfileInfo()->getProfileName();

	    // Get the entry for the first entry in the first row.
	    $rows = $results->getRows();
	    $sessions = $rows[0][0];

	    // Print the results.
	    print "First view (profile) found: $profileName\n";
	    print "Total sessions: $sessions\n";
	  } else {
	    print "No results found.\n";
	  }
	}

}

?>