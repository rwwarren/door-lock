#TODO manual command line arg about which env it is
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
locarray=(${DIR//// })
DEV="dev"
INT="int"
PROD="prod"
PROD_SERVER="idkYET"
ENV=${locarray[2]}
PASSED_IN_ENV=$1
DEPLOYED=false

if [[ ${#PASSED_IN_ENV} > 0 ]]
#if [ ! "$PASSED_IN_ENV" ]
then
  ENV=$PASSED_IN_ENV
fi

if [[ $ENV == $DEV ]]
then
  sudo cp 00-dev-door-lock.conf /etc/apache2/sites-available/00-dev-door-lock.conf
  sudo a2ensite 00-dev-door-lock
  DEPLOYED=true

elif [[ $ENV == $INT ]]
then
  sudo cp 01-int-door-lock.conf /etc/apache2/sites-available/01-int-door-lock.conf
  sudo a2ensite 01-int-door-lock
  DEPLOYED=true

elif [[ $ENV == $PROD ]]
then
  sudo cp 02-prod-test-door-lock.conf /etc/apache2/sites-available/02-prod-test-door-lock.conf
  sudo a2ensite 02-prod-test-door-lock
  DEPLOYED=true

elif [[ $ENV == $PROD_SERVER ]]
then
  sudo cp 10-prod-door-lock.conf /etc/apache2/sites-available/10-prod-door-lock.conf
  sudo a2ensite 10-prod-door-lock
  DEPLOYED=true
else
  echo "please use a specified env"

fi

if $DEPLOYED
then
  echo "deployed apache config for: $ENV"
  sudo service apache2 reload

  echo "apache restart complete"
  echo "DEPLOYMENT SUCCESSFULL!"
fi
