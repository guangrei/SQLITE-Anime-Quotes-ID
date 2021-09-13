<?php
/**
 * Twitter hook receiver
 *
 * @author   guangrei
 * @license  MIT http://opensource.org/licenses/MIT
 * @link     https://github.com/guangrei
 */

class TweetHook
{
    private $db;
    private $data;
    
    public function __construct($database)
    {
        $this->db = new PDO('sqlite:'.$database);
    }
    protected function dbConstruct($query = null)
    {
        if(!isset($query)) {
            $this->db->exec("CREATE TABLE AnimeQuote (id INTEGER PRIMARY KEY, anime VARCHAR NOT NULL, chara VARCHAR NOT NULL, quote VARCHAR NOT NULL)");
        } else {
            $this->db->exec($query);
        }
    }
    public function model1($str)
    {
        if(preg_match("/(.*)\((.*)\/(.*)\)/", $str, $hasil)) {
            list($match,$quote,$chara,$anime) = $hasil;
            $quote = preg_replace("/[^\w\s,?!]+/", "", $quote);
            $this->data = [
            "anime" => trim($anime),
            "chara" => trim($chara),
            "quote" => trim($quote)
            ];
            return true;
        } else {
            return false;
        }
    }
    
    public function model2($str)
    {
        if (preg_match("/(.*)\[(.*)\]/", $str, $hasil)) {
            list($match,$quote,$chara) = $hasil;
            $quote = preg_replace("/[^\w\s,?!]+/", "", $quote);
            $anime = "unknown";
            $this->data = [
            "anime" => trim($anime),
            "chara" => trim($chara),
            "quote" => trim($quote)
            ];
            return true;
        } else {
            return false;
        }
    }
    
    public function model3($str)
    {
        if (preg_match("/(.*)\((.*)\)/", $str, $hasil)) {
            list($match,$quote,$chara) = $hasil;
            $quote = preg_replace("/[^\w\s,?!]+/", "", $quote);
            $anime = "unknown";
            
            $this->data = [
            "anime" => trim($anime),
            "chara" => trim($chara),
            "quote" => trim($quote)
            ];
            return true;
        } else {
            return false;
        }
    }
    
    public function isExists()
    {
        $cek = $this->db->prepare("SELECT COUNT(*) from AnimeQuote WHERE quote = ? LIMIT 1");
        $cek->execute(array($this->data['quote']));
        if ($cek->fetchColumn()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function update()
    {
        if (!$this->isExists()) {
            $stmt = $this->db->prepare("INSERT INTO AnimeQuote (anime, chara, quote) VALUES (?,?,?);");
            $stmt->execute(array($this->data['anime'], $this->data['chara'], $this->data['quote']));
            return json_encode(array('msg' => 'database updated!'));
        } else {
            return json_encode(array('msg' => 'nothing to update!'));
        }
    }
    
    public function count()
    {
        $stmt = $this->db->prepare("SELECT * FROM AnimeQuote");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return count($rows);
    }
    
    public function __destruct()
    {
        unset($this->db);
    }
}
            