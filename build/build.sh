#TODO manual command line arg about which env it is
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
locarray=(${DIR//// })
DEV="dev"
INT="int"
PROD="prod"
PROD_SERVER="idkYET"
ENV=${locarray[2]}

if [ $ENV=$DEV ]
then
sudo cp 00-dev-door-lock.conf /etc/apache2/sites-available/00-dev-door-lock.conf
sudo a2ensite 00-dev-door-lock

elif [ $ENV=$INT ]
then
sudo cp 01-int-door-lock.conf /etc/apache2/sites-available/01-int-door-lock.conf
sudo a2ensite 01-int-door-lock

elif [ $ENV=$PROD ]
then
sudo cp 02-prod-test-door-lock.conf /etc/apache2/sites-available/02-prod-test-door-lock.conf
sudo a2ensite 02-prod-test-door-lock

elif [ $ENV=$PROD_SERVER ]
then
sudo cp 10-prod-door-lock.conf /etc/apache2/sites-available/10-prod-door-lock.conf
sudo a2ensite 10-prod-door-lock

fi

echo "deployed apache config for: $ENV"
sudo service apache2 reload

echo "apache restart complete"
echo "DEPLOYMENT SUCCESSFULL!"

