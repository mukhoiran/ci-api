 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH .'/libraries/JWT.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\SignatureInvalidException;

class User extends CI_Controller {

  private $secret = 'this is key scret';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	public function response($data){
		$this->output
				 ->set_content_type('application/json')
				 ->set_status_header(200)
				 ->set_output(json_encode($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
				 ->_display();

		exit;
	}

	public function register(){
		return $this->response($this->user_model->save());
	}

	public function get_all(){
		return $this->response($this->user_model->get());
	}

	public function get($id){
		return $this->response($this->user_model->get('id', $id));
	}

	public function login(){

    $date = new DateTime();

		if(!$this->user_model->is_valid()){
			return $this->response([
				'success' => false,
				'message' => 'Email or password incorrect'
			]);
		}

		$user = $this->user_model->get('email', $this->input->post('email'));

		//continue with encode the data
		$payload['id'] = $user->id;
		// $payload['email'] = $user->email;
		$payload['iat'] = $date->getTimestamp();
		$payload['exp'] = $date->getTimestamp() + 60*60*2;

    $output['id_token'] = JWT::encode($payload,$this->secret);
    $this->response($output);
	}

  public function check_token(){
    $jwt = $this->input->get_request_header('Authorization');

    try {
      $decoded = JWT::decode($jwt, $this->secret, array('HS256'));
      var_dump($decoded);
    } catch (\Exception $e) {
      return $this->response([
				'success' => false,
				'message' => 'Failed to access token'
			]);
    }

  }

}
