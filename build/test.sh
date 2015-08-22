DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
echo $DIR
SOURCE="${BASH_SOURCE[0]}"
echo $SOURCE
SOURCEE="$0"
echo $SOURCEE
array=(${DIR//// })
for i in "${!array[@]}"
do
  echo "$i=>${array[i]}"
done

echo ${array[2]}
echo $0
echo $1
echo ${#1}
if [ ${#1} > 0 ]
then
  SOURCE="test"

fi

echo $SOURCE
