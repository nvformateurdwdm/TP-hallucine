<?php

class Movie{

    private int $_id;
    private string $_title;
    private string $_imageURL;
    private int $_runtime;
    private string $_description;
    private DateTime $_releaseDate;
    private DateTime $_addedDate;

    private int $_rate = -1;
    private array $_genres = array();

    public function __construct($id, $title, $imageURL, $runtime, $description, $releaseDate, $addedDate){
        $this->_id = $id;
        $this->_title = $title;
        $this->_imageURL = $imageURL;
        $this->_runtime = $runtime;
        $this->_description = $description;
        $this->_releaseDate = new DateTime($releaseDate);
        $this->_addedDate = new DateTime($addedDate);

        // var_dump($this->_releaseDate->format("Y"));
    }

    public function getId(){return $this->_id;}
    public function getTitle(){return $this->_title;}
    public function getImageUrl(){return $this->_imageURL;}
    public function getRuntime(){return $this->_runtime;}
    public function getDescription(){return $this->_description;}
    public function getReleaseDate(){return $this->_releaseDate;}
    public function getAddedDate(){return $this->_addedDate;}

    public function getformatedRuntime():string {
        $formatedRuntime = gmdate("H:i", $this->_runtime);
        $formatedRuntimeArray = explode(":",$formatedRuntime);
        if ($formatedRuntimeArray[0][0] == "0") {
            $formatedRuntimeArray[0] = substr($formatedRuntimeArray[0],1);
        }

        if ($formatedRuntimeArray[1][0] == "0" && $formatedRuntimeArray[1][0] != "0") {
            $formatedRuntimeArray[1] = substr($formatedRuntimeArray[1],1);
        }

        $formatedRuntime = $formatedRuntimeArray[0] . " h " . $formatedRuntimeArray[1] . " min";
        return $formatedRuntime;
    }

    public function getRate(){return $this->_rate;}
    public function setRate(int $rate){$this->_rate = $rate;}

    public function setGenres(array $genres){
        $this->_genres = $genres;
    }

    public function getGenres():array{return $this->_genres;}
    
}

?>