## SQL优化测试工具


支持 CLI && CGI

-  explain
-  show status
-  show profiles


```
/**
     * @param string $sql
     * @param bool|true $show_explain
     * @param bool|false $show_status
     * @param bool|false $show_profiles
     * @return bool
     * @throws \Exception
     */
$aa = new Xmysql('127.0.0.1','test','123456','test');
$aa->execute('select * from `order_2018_4` where symbol = "XAU%" order by id desc limit 10;',1,1,1);

```