<?php
    class Hidroponik{

        // Connection
        private $conn;

        // Table
        private $db_table = "sensor";

        // Columns
        public $id;
        public $suhu;
        public $nutrisi;
        public $air;
        public $pH;
        public $created_at;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL DATA
        public function getLogData(){
            $sqlQuery = "SELECT id, suhu, nutrisi, air, pH, created_at FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        // CREATE
        public function createLogData(){
            $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        suhu = :suhu, 
                        nutrisi = :nutrisi,
                        air = :air,
                        pH = :pH";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->suhu=htmlspecialchars(strip_tags($this->suhu));
            $this->nutrisi=htmlspecialchars(strip_tags($this->nutrisi));
            $this->air=htmlspecialchars(strip_tags($this->air));
            $this->pH=htmlspecialchars(strip_tags($this->pH));
        
            // bind data
            $stmt->bindParam(":suhu", $this->suhu);
            $stmt->bindParam(":nutrisi", $this->nutrisi);
            $stmt->bindParam(":air", $this->air);
            $stmt->bindParam(":pH", $this->pH);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

        // fetch single
        public function getSingleLogData(){
            $sqlQuery = "SELECT
                        id, 
                        suhu, 
                        nutrisi, 
                        air, 
                        pH, 
                        created_at
                      FROM
                        ". $this->db_table ."
                    WHERE 
                       id = ?
                    LIMIT 0,1";

            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
			//error handling
			if($stmt->errorCode() == 0) {
				while(($dataRow = $stmt->fetch(PDO::FETCH_ASSOC)) != false) {
					$this->suhu = $dataRow['suhu'];
					$this->nutrisi = $dataRow['nutrisi'];
					$this->air = $dataRow['air'];
					$this->pH = $dataRow['pH'];
					$this->created_at = $dataRow['created_at'];
				}
			} else {
				$errors = $stmt->errorInfo();
				echo($errors[2]);
			}
			
            //$dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
            //$this->suhu = $dataRow['suhu'];
            //$this->kelembaban = $dataRow['kelembaban'];
            //$this->created_at = $dataRow['created_at'];
        }        

        // Edit Data
        public function updateDataLog(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        suhu = :suhu, 
                        nutrisi = :nutrisi,  
                        air = :air,  
                        pH = :pH,  
                        created_at = :created_at
                    WHERE 
                        id = :id";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->suhu=htmlspecialchars(strip_tags($this->suhu));
            $this->nutrisi=htmlspecialchars(strip_tags($this->nutrisi));
            $this->air=htmlspecialchars(strip_tags($this->air));
            $this->pH=htmlspecialchars(strip_tags($this->pH));
            $this->created_at=htmlspecialchars(strip_tags($this->created_at));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind data
            $stmt->bindParam(":suhu", $this->suhu);
            $stmt->bindParam(":nutrisi", $this->nutrisi);
            $stmt->bindParam(":air", $this->air);
            $stmt->bindParam(":pH", $this->pH);
            $stmt->bindParam(":created_at", $this->created_at);
            $stmt->bindParam(":id", $this->id);
        
            if($stmt->execute()){
               $itemCount = $stmt->rowCount();
			   if($itemCount > 0){
					return true;
				}else{
					return false;
				}
            }
            return false;
        }

        // DELETE
        function deleteLogData(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            $stmt->bindParam(1, $this->id);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }

    }
?>

