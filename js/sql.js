var arr=[
    {name:"awen1", sex:1, age:12},
    {name:"awen2", sex:1, age:13}
   ];

function find(arr){
    var i ;
    for( i = 0; i < arr.length; i ++ ){
        var item = arr[i];
        if ($express){
            return item;
        }
    }    
    return false;
}

function query(preg){
    var code = find.toString().replace('$express', preg);
    return eval('0,' + code); /* fix for Anonymous Functions */
}

console.log(query('item.name=="awen1"')(arr).sex);
console.log(query('item.name=="awen2"')(arr).age);
