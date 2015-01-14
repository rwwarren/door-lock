DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
TYPE="install"

mv web/inc/mysqlUserTEST.php web/inc/mysqlUser.php
mv web/inc/variablesTEST.php web/inc/variables.php
mv web/src/coveralls.yml web/src/.coveralls.yml
mkdir web/src/properties
cp web/build/example.properties web/src/properties/secure.ini
PASSED_IN_ARG=$1
#if [[ $PASSED_IN_ARG == $TYPE ]]
#then
#  $DIR/build/build.sh
#fi
