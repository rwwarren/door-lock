#!/usr/bin/env bash
ROOT=$(pwd)

if [ ! -n "${env}" ]; then
	env="development"
fi

user="root"

JAR="./target/api-lock-1.0-SNAPSHOT.jar"
CONFIG="drop.yml"

CMD="java -Dnewrelic.environment=$env -javaagent:$ROOT/target/dependency/newrelic-agent.jar -jar $JAR server $CONFIG"

stdout_log="$ROOT/$env.log"
stderr_log="$ROOT/$env.err"

pid_file="$ROOT/$env.pid"

isRunning() {
	[ -f "$pid_file" ] && ps `cat $pid_file` > /dev/null 2>&1
}

case $1 in
	start)
		if isRunning; then
			echo "Already started"
		else
			echo "Starting api"
			$CMD > "$stdout_log" 2> "$stderr_log" & echo $! > "$pid_file"
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
