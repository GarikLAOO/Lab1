<?php  class CSV { 

     private $_csv_file = null; 


    public function __construct($csv_file) {         
        
        if (file_exists($csv_file)) {  

            $this->_csv_file = $csv_file;  

        }else throw new Exception("File not found");  

    } 


    public function setCSV(Array $csv) { 

        $handle = fopen($this->_csv_file, "a"); 

        foreach ($csv as $value) {              

            fputcsv($handle, explode(",", $value), ",", "\"", "\\");

        }         
        fclose($handle);  

    } 

    public function getCSV() { 

        $handle = fopen($this->_csv_file, "r"); 


        $array_line_full = array();          
        
        while (($line = fgetcsv($handle, 0, ",", "\"", "\\")) !== FALSE) {  
            $array_line_full[] = $line;  
        }    

        fclose($handle);          
        
        return $array_line_full;  

    } 


}  

    try { 
        $csv = new CSV("task7.csv"); 
        $get_csv = $csv->getCSV();  

        foreach ($get_csv as $value) {          
            echo "Last name: " . (isset($value[0]) ? $value[0] : '') . "<br/>";         
            echo "First name: " . (isset($value[1]) ? $value[1] : '') . "<br/>";         
            echo " Position: " . (isset($value[2]) ? $value[2] : '') . "<br/>";         
            echo " Salary: " . (isset($value[3]) ? $value[3] : '') . "<br/>";         
            echo "--------<br/>"; 
        } 

        $arr = array("Ponomarenko,Ivan,,12000"); 
        $csv->setCSV($arr); 

    } catch (Exception $e) {     
        echo "Помилка: " . $e->getMessage(); 
    }

?> 