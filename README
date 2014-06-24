/* env package */

PROBLEMS:
    1. have you ever been in sitution that use explode(",",$str) for many time to convert csv string into array ?
    2. have you just find that when you write an array ($a,$b,$c) into mysql, you need a lot of simulate lines to be write ?
    3. say you are faceing problem 2, then you find an addition log need to be add: monitor the input/output of mysql , what the fuck ...

class IO{
    public function push(){}
    public function pop(){}     //stack

    public function query(){}   //db, http

    public function open(){}    //file, socket
    public function close(){}
    public function set_attr(){}

    public function set(){}     //web, memcached
    public function get(){}

    public function read(){}    //all
    public function write(){}

    public function flush(){}   //all
    public function clean(){}

}

$env = array(
   "stdin" => array("CLASS"=>"IoWeb"),
   "stdout" => array("CLASS"=>"IoWeb"),
   "txapi" => array("CLASS"=>"IoSimpleHttp", "host"=>"pre.tencent.com"),

   "role_info" => array("CLASS"=>"IoMpi", "mpidb"=>"mpidb_00", "mpicache"=>"mpicache_01"),
   "role_friend" => array("CLASS"=>"IoMpi", "mpidb"=>"mpidb_00", "mpicache"=>"mpicache_01"),
   "role_map0" => array("CLASS"=>"IoMpi", "mpidb"=>"mpidb_00[+mpidb_01]", "mpicache_01[+cache_02]"),
   "role_map1" => array("CLASS"=>"IoMpi", "mpidb"=>"mpidb_00", "mpicache_01"),

   "mpidb_00" => array("CLASS"=>"IoMpiDB", "conn"=>"db0", "dbname"=>"role_info", "table_prefix"=>"m_role_info_", "table_mod"=>"hex_02"),
   "mpidb_01" => array("CLASS"=>"IoMpiDB", "conn"=>"db0", "dbname"=>"role_map", "table_prefix"=>"m_role_map_", "table_mod"=>"hex_02"),
   "mpidb_02" => array("CLASS"=>"IoMpiDB", "conn"=>"db1", "dbname"=>"role_map","table_prefix"=>"m_role_map_", "table_mod"=>"hex_02"),
   "mpidb_03" => array("CLASS"=>"IoMpiDB", "conn"=>"db0", "dbname"=>"role_map","table_prefix"=>"m_role_map_", "table_mod"=>"hex_02"),
   "mpidb_04" => array("CLASS"=>"IoMpiDB", "conn"=>"db1", "dbname"=>"role_map","table_prefix"=>"m_role_map_", "table_mod"=>"hex_02"),

   "mpicache_01" => array("conn"=>"cache0", "prefix"=>"role"),
   "mpicache_02" => array("conn"=>"cache0", "prefix"=>"role"),
   "mpicache_03" => array("conn"=>"cache0", "prefix"=>"role"),
   "mpicache_04" => array("conn"=>"cache0", "prefix"=>"role"),

   "db0" => array("CLASS"=>"IoSimpleMysql", "host"=>127.0.0.1, "port"=>3306, "user"=>"awen", "passwd"=>"awen"),
   "db1" => array("CLASS"=>"IoSimpleMysql", "host"=>127.0.0.1, "port"=>3306, "user"=>"awen", "passwd"=>"awen"),
   "db2" => array("CLASS"=>"IoSimpleMysql", "host"=>127.0.0.1, "port"=>3306, "user"=>"awen", "passwd"=>"awen"),
   "db3" => array("CLASS"=>"IoSimpleMysql", "host"=>127.0.0.1, "port"=>3306, "user"=>"awen", "passwd"=>"awen"),

   "cache0" => array("CLASS"=>"IoMemcached", "host"=>"127.0.0.1"),
   "cache1" => array("CLASS"=>"IoMemcached", "host"=>"127.0.0.1"),
   "cache2" => array("CLASS"=>"IoMemcached", "host"=>"127.0.0.1"),


   "__EXP_HANDLER" => array("CLASS"=>"StdExpHandler", "io"=>"stdout"),
   "__ASSERT_HANDLER" => array("CLASS"=>"StdAssertHandler", "io"=>"stdout"),
);

we_set_env("RELEASE", new MoEvn($env));
we_set_env("DEBUG", new MoEvn($env));
we_set_currenv("RELEASE");




function run_myjob(){
    we_get("stdin", "uid");
    we_get("cache0", $uid);
    we_set_attr("cache0", "IS_COMPRESS", "COMPRESSED");
    we_set("cache0", $uid, $expired, "EXPIRED");

    we_query("txapi", "/mobile/test", array("name"=>"foo"), "POST");
    we_query("db0", "select * from userinfo where uid=?", array($uid));
}

class MyJob {
    public function run(){
        we_io("stdin")->get("uid");
        we_io("cache0")->get($uid);
        we_io("txapi")->query("/mobile/test", array("name"=>"foo"), "POST");

        we_io("role_info")->get($uid);
        we_io("role_friend")->set($uid, $friend);
        we_io("role_map0")->set($uid, $map);
        we_io("role_map1")->set($uid, $map);
    }
}


we_run("my.first.job");
we_flush();
