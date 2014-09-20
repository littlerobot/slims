#!/bin/bash
DIR="$( cd "$( dirname "$0" )" && pwd )"

echo "Attempting to restart ready-made database container"
if  (docker start slims-db &> /dev/null)
    then
        echo "Started OK"
    else
        echo "Docker start slims-db failed, assuming this is first run. Building..."
        if ! (docker build -t littlerobot/slims-db $DIR/database)
            then
                echo "Build failed."
                exit
        fi
        docker run -d --name slims-db littlerobot/slims-db
fi

echo "Attempting to restart ready-made web container"
if  (docker start slims-web &> /dev/null)
    then
        echo "Started OK"
    else
        echo "Docker start slims-web failed, assuming this is first run. Building..."
        if ! (docker build -t littlerobot/slims-web $DIR)
            then
                echo "Build failed."
                exit
        fi
        docker run --rm -it --name slims-web -v $DIR:/var/www/slims -p 80:80 --link slims-db:slims-db littlerobot/slims-web
fi
