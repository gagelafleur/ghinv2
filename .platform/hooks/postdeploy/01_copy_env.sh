#!/usr/bin/env bash

# Move the files you created in /tmp into the desired directories.
/bin/cp /home/ec2-user/.env.elasticbeanstalk /var/www/html/.env
/bin/chown webapp /var/www/html/.env
/bin/chgrp webapp /var/www/html/.env
# other bash commands
