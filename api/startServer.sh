#!/usr/bin/env bash

#java -Dnewrelic.environment=local -javaagent:/Users/ryan/Documents/door-lock/api/target/dependency/newrelic-agent.jar -jar ./target/api-lock-1.0-SNAPSHOT.jar server

ROOT="/home/ubuntu/wrixtonCom/dev/door-lock/api"

env="development"

user="root"

JAR="./target/api-lock-1.0-SNAPSHOT.jar"
CONFIG="drop.yml"

CMD="java -Dnewrelic.environment=$env  -javaagent:$ROOT/target/dependency/newrelic-agent.jar -jar $JAR server $CONFIG"


#name=`basename $0`

stdout_log="$ROOT/$env.log"
#stdout_log="/var/log/$name.log"
stderr_log="$ROOT/$env.err"
#stderr_log="/var/log/$name.err"

pid_file="$ROOT/$envpid"

isRunning() {
	[ -f "$pid_file" ] && ps `cat $pid_file` > /dev/null 2>&1
}



case $1 in
	start)
		if isRunning; then
			echo "Already started"
		else
			echo "Starting $name"
			sudo -u "$user" $CMD > "$stdout_log" 2> "$stderr_log" & echo $! > "$pid_file"
			if ! isRunning; then
				echo "Unable to start, see $stdout_log and $stderr_log"
				exit 1
			fi
		fi
	;;
	stop)
		if isRunning; then
			echo "Stopping $name"
			kill `cat $pid_file`
			rm "$pid_file"
		else
			echo "Not running"
		fi
	;;
	restart)
		$0 stop
		$0 start
	;;
esac

exit 0