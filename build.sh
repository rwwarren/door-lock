DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

mv web/inc/mysqlUserTEST.php web/inc/mysqlUser.php
mv web/inc/variablesTEST.php web/inc/variables.php
mv web/coveralls.yml web/.coveralls.yml
PASSED_IN_ARG=$1
if [[ $PASSED_IN_ARG == "install" ]]
then
  $DIR/build/build.sh
fi
