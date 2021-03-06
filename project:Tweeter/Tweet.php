<?php

include_once 'Connection.php';

class Tweet {


//  Tomek Zwróc uwagę na PHPDoc '@var type' nie moze byc. Musisz podac wlasciwy typ np: String, Integer, Array
    /**
     *
     * @var type 
     */
    private $id = -1;

    /**
     *
     * @var type 
     */
    private $userId = "";

    /**
     *
     * @var type 
     */
    private $text = "";

    /**
     *
     * @var type 
     */
    private $creationDate = "";

    /**
     * 
     * @param type $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * 
     * @param type $text
     */
    public function setText($text) {
        $this->text = $text;
    }

    /**
     * 
     * @param type $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    /**
     * 
     * @param type $creationDate
     */
    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    /**
     * 
     * @return type
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @return type
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * 
     * @return type
     */
    public function getText() {
        return $this->text;
    }

    /**
     * 
     * @return type
     */
    public function getCreationDate() {
        return $this->creationDate;
    }

    /**
     * 
     * @param type $tweetId
     * @return \Tweet
     */
    static public function getById($tweetId) {

        $connection = new Connection();

        $id = $connection->getConnection()->real_escape_string($tweetId);

        $sql = "SELECT * FROM Tweets WHERE id=$id";

        $result = $connection->querySql($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $tweet = new Tweet();
            $tweet->setId($row['id']);
            $tweet->setUserId($row['userId']);
            $tweet->setText($row['text']);
            $tweet->setCreationDate($row['creationDate']);
            return $tweet;
        }
        return null;
    }

    /**
     * 
     * @param type $userId
     * @return \Tweet
     */
    static public function getByUserId($userId) {

        $connection = new Connection();

        $id = $connection->getConnection()->real_escape_string($userId);

        $sql = "SELECT * FROM Tweets WHERE userId=$id";

        $allTweets = array();

        $result = $connection->querySql($sql);

//        Lepiej daj $result->num_rows > 0, czasmi funkcje lubia dac -1, albo np. Jakis string.
        if ($result == true && $result->num_rows != 0) {

            foreach ($result as $row) {
                $userTweet = new Tweet();
                $userTweet->setId($row['id']);
                $userTweet->setUserId($row['userId']);
                $userTweet->setText($row['text']);
                $userTweet->setCreationDate($row['creationDate']);

                $allTweets[] = $userTweet;
            }
        }
        return $allTweets;
    }

    /**
     * 
     * @return \Tweet
     */
//    Ta metoda powinna nazywa sie getAll bez slowa Tweets, bo to wynika z kontekstu w jakiej jest metoda
    static public function loadAllTweets() {

        $connection = new Connection();

        $sql = "SELECT * FROM Tweets";

        $allTweets = array();

        $result = $connection->querySql($sql);

        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $tweets = new Tweet();
                $tweets->setId($row['id']);
                $tweets->setUserId($row['userId']);
                $tweets->setText($row['text']);
                $tweets->setCreationDate($row['creationDate']);

                $allTweets[] = $tweets;
            }
        }
        return $allTweets;
    }

    /**
     * 
     * @return boolean
     */
    public function saveToDB() {

        $connection = new Connection();

        if ($this->id == -1) {

            $sql = "INSERT INTO `Tweets`(`userId`, `text`, `creationDate`) VALUES ('$this->userId', '$this->text', '$this->creationDate')";
            $result = $connection->querySql($sql);

            if ($result) {

                $this->id = $connection->getConnection()->insert_id;

                return true;
            }
        }
//wystarczy samo return;
        return false;
    }

    /**
     * 
     * @param type $id
     */
//    PHPDoc do poprawy
    static public function delete($id) {

        $conection = new Connection();

        $stmt = $conection->getConnection()->prepare("DELETE FROM Tweets WHERE id = ?");
        $stmt->bind_param("i", $id);

//execute() zwraca tru lub false w zaleznosci czy sie udalo czy nie, dlatego zamien na 'return $stmt->execute()'
        $stmt->execute();


    }

}
