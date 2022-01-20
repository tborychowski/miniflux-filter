<?php

exec('bash -c "php filter-job.php -b > /dev/null 2>&1 &"');
header('location: filters');
