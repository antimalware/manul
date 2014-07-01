
for f in *.test.php; 
do 
    test_result=` phpunit $f 2>/dev/null | grep 'OK' `
    echo $f $test_result
    echo;

done