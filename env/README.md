 ##env framework
 
 let's see how to use "env" framework:
 
 1. installing env framework;
 <?php 
     // before any operations on env(), you should include the env.php , and init it's root dir
     require __DIR__ . "/env/env.php";
     env()->root = __DIR__;
     
     //now do anything you like .....

?>

2.  using the class lib
    as you can see, there are many subdirs in env/ , like 'curl', 'hash', 'queue', 'stack', ...., so where did they come from ? 
    the answer is: "whenever an new "data operation behavior" turns out, a new dir with a stable interface is added "

    curl is for   "request a server, and then fetch the response"
    queue is for  "pushing a data, shifting a data, (first-in-first-out)"
    stack is for  "pushing a data, poping a data,  (first-in-last-out)"
    hash is for   "get/set value by a key"
    stream is for "simply read in and write in"
    db is for     "quering from a database"
    client is for "connect to a server, do 'request-response' for servaler times if needed, and then close"
    
    router is for "explain a uri, and get script_filename, output_format from it"
    caller is for "load module from script_filename, and pass params to it, and fetch result from it "
    
    only 'router' and 'caller' are used for env routing and env calling, the others are all for you, sir ....
   
   
3.  loading a source object into env
    forexample, when using mysql, the "source loading" is very simple, two ways will both work:
    
    <?php
      /* 1.  make mysql as one of env's attributes */
      env()->db0 = new \env\db\mysqli("127.0.0.1", 3306, "user", "passwd");
      env()->db0->query("select * from fool");

      /* 2.  make an array of mysql objects as one of env's attribute */
      env()->dbs = array(
        "user" => new \env\db\mysqli("127.0.0.1", 3306, "user", "passwd"),
        "admin" => new \env\db\mysqli("127.0.0.1", 3307, "user", "passwd"),
      );
      env()->dbs['user']->query("select * from fool");
      
      
4.  developing a new lib class
    when you want to develop a new lib class, the rule's simple:
      1. implements the interface in the lib dir
      2. only methods of the interface should be public, exception __construct()
      3. only methods should be public ( it means no public attributes, no __get(), __set() to access private attributes)

    forexample, when a new curl class is wrote out, it should be like this:
    
    <?php
    /* use namespace with path '\env\xxx' , so that env could easily find this class */
    namespace \env\curl;
    
    class example_curl implements \env\curl\icurl {
    
      /* no public attributes */
      private $host;
      private $port;
      
      /* __construct can be public */
      public function __construct($host, $port){
      }
    
      /* implements public interface method */
      public function request($uri, array $params=array()){
      }
    
      /* other method should be private */  
      private function somefunc_ifyouneed(){
      }
    }
    
5.  developing a new lib
    when you find all of the current interfaces are not able to descripte what you're doing, you can write your own lib totally, the rule is :
    1. make a dir with lower-case-character dirname, mark as $dir; forexample : foo_me
    2. touch a file with name "i$dir.php"; like : ifoo_me.php
    3. define your interfaces methods in "i$dir.php"
    4. follow the role[4] to add classes in this dir
    
6.  about `router` and `caller`
    env has a default module `caller` when init, you can chose another one to replace it; or you can implement your own caller too;
    env need you to specify a router, there is a default one in dir `router`, take a look at it ; if it's not what you need, implement another one -- don't forget to follow the rules
    
    
    

