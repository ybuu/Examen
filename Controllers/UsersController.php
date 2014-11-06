<?php

class User extends AppController{

	public function __contruct(){
		parent::__contruct();
	}

	public function index(){
		$users = $this->User->find("users", "all");	
		$this->set("users", $users);
	}
	
	public function add(){
		if($_POST){
			if($this->User->save("users", $_POST)){
				$this->redirect(array("controller"=>"users", "action"=>"index"));
				
				//Send mail to user
				$mail = new PHPMailer;
				
				$mail->From = 'ybuu.093@gmail.com';
				$mail->FromName = 'Prueba de registro de usuarios';
				//$mail->addAddress('ybuu.093@gmail.com', '');
				$mail->addAddress($_POST['email'], $_POST['first_name']);
				$mail->addReplyTo('ybuu.093@gmail.com', 'Information');
				//$mail->addCC('cc@example.com');
				//$mail->addBCC('bcc@example.com');
				$mail->isHTML(true);
				$mail->Subject = 'Test Account';
				$mail->Body    = 'Hello. <b></b>';
				
				
				if(!$mail->send()) {
					echo 'Message could not be sent.';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} else {
					echo 'Message has been sent. Change the world. ';
				}
				
			}else{
				$this->redirect(array("controller"=>"users", "action"=>"add"));
			}
		}
	}

	public function edit($id = null){
		if($_POST){
			$filter = new Validations();
			$pass = new Password();

			$_POST["password"] = $filter->sanitizeText($_POST["password"]);
			$_POST["password"] = $pass->getPassword($_POST["password"]);

			if($this->User->update("users", $_POST)){
				$this->redirect(array("controller"=>"users", "action"=>"index"));
			}else{
				$this->redirect(array("controller"=>"users", "action"=>"edit"));
			}
		}		
		$user = $this->User->find("users", "first", array(
			"conditions" => "users.id=$id"
		));
		$this->set("user", $user);

		//$groups = $this->User->find("groups", "all");
		//$this->set("groups", $groups);
	}

	public function login(){
		if($_POST){
			$pass = new Password();
			$filter = new Validations();
			$auth = new Authorization();

			$username = $filter->sanitizeText($_POST["username"]);
			$password = $filter->sanitizeText($_POST["password"]);

			$options['conditions'] = " username = '$username'";
			$user = $this->User->find("users", "first", $options);

			if($pass->isValid($password, $user['password'])){
				$auth->login($user);
				$this->redirect(array("controller"=>"users", "action"=>"index"));
			}else{
				echo "Usuario Invalido";
			}
		}
	}

	public function logout(){
		$auth = new Authorization();
		$auth->logout();
	}	

	public function delete($id = null){
		
	}
	private function sendMail($email, $name){
	$mail = new PHPMailer;


$mail->From = "team@app.com";
$mail->FromName = "Team app";
$mail->AddAddress($email, $name);

$mail->$WordWrap = 50;
$mail->isHTML(true);



$mail->Subject = 'Usuario Agregado';
$mail->Body    = 'Usuario agregado</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
}	
}