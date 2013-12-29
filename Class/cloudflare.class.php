<?php
class cloudflare{
	private $zone; private $mail; private $api; private $ip;
	private $url = "https://www.cloudflare.com/api_json.html";

	public function __construct($zone, $mail, $api, $setip = true){
		if($setip){
			$this->ip = str_replace("\n", "", file_get_contents("http://icanhazip.com/"));
		}
		else
		{
			$this->ip = "0.0.0.0";
		}

		$this->zone = $zone;
		$this->mail = $mail;
		$this->api = $api;
	}

	private function POST($fields){
		$fields_string = "";

		foreach($fields as $key=>$value){$fields_string .= $key.'='.$value.'&';}
		rtrim($fields_string, '&');

		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL, $this->url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}

	public function update($subdomain){
		$fields = array(
			"a" => "rec_load_all",
			"tkn" => $this->api,
			"email" => $this->mail,
			"z" => $this->zone
		);

		$data = json_decode($this->POST($fields));

		if($data->result  != "success"){
			return false;
		}

		$data = $data->response->recs->objs;

		foreach($data as $record){
			if($record->name  == $subdomain){
				break;
			}
		}

		if($record->name  != $subdomain){
			return false;
		}

		$fields = array(
			"a" => "rec_edit",
			"tkn" => $this->api,
			"id" => $record->rec_id,
			"email" => $this->mail,
			"z" => $this->zone,
			"type" => "A",
			"name" => $record->display_name,
			"content" => $this->ip,
			"service_mode" => "0",
			"ttl" => "1"
		);

		$data = json_decode($this->POST($fields));

		if($data->result  != "success"){
			return false;
		}

		print_r($data);

		return true;
	}

	public function getip(){
		return $this->ip;
	}

	public function setip($ip){
		$this->ip = $ip;
		return true;
	}
}
?>
