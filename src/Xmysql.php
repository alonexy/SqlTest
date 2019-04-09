<?php
namespace Alonexy;

use jc21\CliTable;
use jc21\CliTableManipulator;
use Nette\Database\Connection;
use Twig\Loader\FilesystemLoader;

Class Xmysql
{
    private $DB;
    private $hots;
    private $user;
    private $password;
    private $dbname;
    private $buffers;

    public function __construct($host, $user, $password, $dbname, array $options = [

    ])
    {
        $this->hots     = $host;
        $this->user     = $user;
        $this->password = $password;
        $this->dbname   = $dbname;
        $dsn            = "mysql:host={$host};dbname={$dbname};charset=utf8";
        $this->DB       = new Connection($dsn, $user, $password, $options);
    }

    /**
     * @param string $sql
     * @param bool|true $show_explain
     * @param bool|false $show_status
     * @param bool|false $show_profiles
     * @return bool
     * @throws \Exception
     */
    public function execute($sql = '', $show_explain = true, $show_status = false, $show_profiles = false)
    {
        if (empty($sql)) {
            throw new \Exception("Sql is nil.");
        }
        if (!preg_match('/;$/', $sql)) {
            throw new \Exception("Sql 必须是；结束");
        }
        if($show_explain){
            list($keys,$data) = $this->getExplain($sql);
            $this->Dispaly($keys,$data,'red');
        }
        if($show_status){
            list($keys,$data) = $this->getShowStatus($sql);
            $this->Dispaly($keys,$data,'blue');
        }
        if($show_profiles){
            list($keys,$data) = $this->getShowProfiles($sql);
            $this->Dispaly($keys,$data);
        }
        return $this->run();
    }
    private function Dispaly($keys,$data,$tableColor='green',$HeaderColor='cyan')
    {
        $this->buffers[] = [$keys,$data,$tableColor,$HeaderColor];
    }
    private  function run(){
        if(PHP_SAPI == 'cli'){
            foreach($this->buffers as $k => $buffer){
                $table = new CliTable();
                $table->setTableColor($buffer[2]);
                $table->setHeaderColor($buffer[3]);
                foreach($buffer[0] as $val){
                    $table->addField($val, $val, false,'white');
                }
                $table->injectData($buffer[1]);
                $table->display();
            }
        }else{ //fpm-fcgi

            $loader = new FilesystemLoader(__DIR__.'/../tpl');
            $twig = new \Twig\Environment($loader);

            echo $twig->render('index.html', ['lists' => $this->buffers]);
        }
       return true;
    }
    private function getExplain($sql)
    {
        $results = $this->DB->fetchAll("explain $sql");
        $list = [];
        foreach($results as $val){
            $list[] = $this->obj2arr($val);
        }
        return [array_keys($list[0]),$list];
    }

    public function getVersion()
    {
        $results = $this->DB->fetch('select version() as vv;');
        return $results->vv;
    }

    private function getShowStatus($sql)
    {
        $rr  = $this->DB->query('flush status')->getQueryString();
        $rr1 = $this->DB->query("$sql")->getQueryString();
        $rr2 = $this->DB->fetchAll('show session status like "Handler_read%";');
        $list = [];
        foreach($rr2 as $val){
            $list[$val['Variable_name']] = $val['Value'];
        }
        return [array_keys($list),[$list]];
    }

    public function getShowProfiles($sql)
    {
        $rr  = $this->DB->query("set profiling = 1;")->getQueryString();
        $rr1 = $this->DB->query("$sql")->getQueryString();
        $rr2 = $this->DB->query("show profiles;")->getQueryString();
        $results = $this->DB->fetchAll("show profile CPU,MEMORY,SWAPS,SOURCE,BLOCK IO for query 1;");
        $list = [];
        foreach($results as $val){
            $list[] = $this->obj2arr($val);
        }
        return [array_keys($list[0]),$list];
    }
    public function obj2arr($arr)
    {
        $arr = json_encode($arr);
        $arr = json_decode($arr,1);
        return $arr;
    }
}


?>