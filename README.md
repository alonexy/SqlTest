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
```
╔════╤═════════════╤══════════════╤════════════╤═══════╤═══════════════╤═════════╤═════════╤═════╤══════╤══════════╤═════════════╗
║ id │ select_type │ table        │ partitions │ type  │ possible_keys │ key     │ key_len │ ref │ rows │ filtered │ Extra       ║
╟────┼─────────────┼──────────────┼────────────┼───────┼───────────────┼─────────┼─────────┼─────┼──────┼──────────┼─────────────╢
║ 1  │ SIMPLE      │ order_2018_4 │            │ index │               │ PRIMARY │ 8       │     │ 10   │ 10       │ Using where ║
╚════╧═════════════╧══════════════╧════════════╧═══════╧═══════════════╧═════════╧═════════╧═════╧══════╧══════════╧═════════════╝
╔════════════════════╤══════════════════╤═══════════════════╤═══════════════════╤═══════════════════╤══════════════════╤═══════════════════════╗
║ Handler_read_first │ Handler_read_key │ Handler_read_last │ Handler_read_next │ Handler_read_prev │ Handler_read_rnd │ Handler_read_rnd_next ║
╟────────────────────┼──────────────────┼───────────────────┼───────────────────┼───────────────────┼──────────────────┼───────────────────────╢
║ 0                  │ 1                │ 1                 │ 0                 │ 108               │ 0                │ 0                     ║
╚════════════════════╧══════════════════╧═══════════════════╧═══════════════════╧═══════════════════╧══════════════════╧═══════════════════════╝
╔══════════════════════╤══════════╤══════════╤════════════╤══════════════╤═══════════════╤═══════╤═══════════════════════╤══════════════════════╤═════════════╗
║ Status               │ Duration │ CPU_user │ CPU_system │ Block_ops_in │ Block_ops_out │ Swaps │ Source_function       │ Source_file          │ Source_line ║
╟──────────────────────┼──────────┼──────────┼────────────┼──────────────┼───────────────┼───────┼───────────────────────┼──────────────────────┼─────────────╢
║ starting             │ 0.00004  │ 0.000035 │ 0.000005   │ 0            │ 0             │ 0     │                       │                      │             ║
║ checking permissions │ 0.000005 │ 0.000004 │ 0.000002   │ 0            │ 0             │ 0     │ check_access          │ sql_authorization.cc │ 806         ║
║ Opening tables       │ 0.000009 │ 0.000008 │ 0.000001   │ 0            │ 0             │ 0     │ open_tables           │ sql_base.cc          │ 5649        ║
║ init                 │ 0.000029 │ 0.000028 │ 0.000001   │ 0            │ 0             │ 0     │ handle_query          │ sql_select.cc        │ 121         ║
║ System lock          │ 0.000007 │ 0.000005 │ 0.000001   │ 0            │ 0             │ 0     │ mysql_lock_tables     │ lock.cc              │ 323         ║
║ optimizing           │ 0.000012 │ 0.000011 │ 0.000001   │ 0            │ 0             │ 0     │ optimize              │ sql_optimizer.cc     │ 151         ║
║ statistics           │ 0.000012 │ 0.000012 │ 0.000001   │ 0            │ 0             │ 0     │ optimize              │ sql_optimizer.cc     │ 367         ║
║ preparing            │ 0.00001  │ 0.000009 │ 0.000001   │ 0            │ 0             │ 0     │ optimize              │ sql_optimizer.cc     │ 475         ║
║ Sorting result       │ 0.000004 │ 0.000003 │ 0.000001   │ 0            │ 0             │ 0     │ make_tmp_tables_info  │ sql_select.cc        │ 3829        ║
║ executing            │ 0.000003 │ 0.000002 │ 0.000001   │ 0            │ 0             │ 0     │ exec                  │ sql_executor.cc      │ 119         ║
║ Sending data         │ 0.000157 │ 0.000156 │ 0.000001   │ 0            │ 0             │ 0     │ exec                  │ sql_executor.cc      │ 195         ║
║ end                  │ 0.000004 │ 0.000002 │ 0.000001   │ 0            │ 0             │ 0     │ handle_query          │ sql_select.cc        │ 199         ║
║ query end            │ 0.000004 │ 0.000004 │ 0.000001   │ 0            │ 0             │ 0     │ mysql_execute_command │ sql_parse.cc         │ 5005        ║
║ closing tables       │ 0.000006 │ 0.000004 │ 0.000001   │ 0            │ 0             │ 0     │ mysql_execute_command │ sql_parse.cc         │ 5057        ║
║ freeing items        │ 0.000018 │ 0.000007 │ 0.000012   │ 0            │ 0             │ 0     │ mysql_parse           │ sql_parse.cc         │ 5631        ║
║ cleaning up          │ 0.000007 │ 0.000005 │ 0.000001   │ 0            │ 0             │ 0     │ dispatch_command      │ sql_parse.cc         │ 1902        ║
╚══════════════════════╧══════════╧══════════╧════════════╧══════════════╧═══════════════╧═══════╧═══════════════════════╧══════════════════════╧═════════════╝
```