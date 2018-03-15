<?php
class Contact extends Controller {
    private $views;
    private $data = array();
    function __construct(){
        parent::__construct();
        $this->views = load_core("Views");
        $this->data["pageTitle"] = "Contact us";
    }

    public function index(){
        $this->views->template("contact/contactform");
    }

    public function send(){
        $error = array();
        $data = array();
        $fieldNames = array(
            "name" => 4,
            "email" => 10,
            "reason" => 0,
            "message" => 20,
        );
        foreach($fieldNames as $field => $length){
            if(isset($_POST[$field]) and strlen($_POST[$field]) >= $length) {
                if ($field == "email" and !filter_var($field, FILTER_VALIDATE_EMAIL) === false) {
                    $error[$field] = "Email must be a correct email adress.";
                } elseif($field == "reason" and $_POST["reason"] == "") {
                    $error[$field] = "You must select a reason";
                } else {
                    $data[$field] = $_POST[$field];
                }
            } else {
                #if($field != "reason") {
                    $error[$field] = $field . ' must not be empty and atleast ' . $length . ' letters long.';
                #}
            }
        }

        if(count($error) > 0){
            $this->data["error"] = $error;
            $this->data["data"]  = $data;
            $this->views->template("contact/contactform", $this->data);
        } else {
            /* No errors, lets store it */
            $this->db->query("INSERT INTO contacts (name, email, message, date) VALUES(:name, :email, :message, :date)");
                $this->db->bind(":name", $data["name"]);
                $this->db->bind(":email", $data["email"]);
                $this->db->bind(":message", nl2br($data["message"]));
                $this->db->bind(":date", time());
            $this->db->execute();
            if($this->db->lastInsertId() != null){
                $this->data["email"] = $data["email"];
                $this->views->template("contact/successful", $this->data);
            } else {
                $this->views->template("contact/unsucessful", $this->data);
            }
        }

    }
}