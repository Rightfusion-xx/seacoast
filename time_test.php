<?php
set_time_limit(1200);
echo 'working';
flush();
for($i=1;$i<10;$i++)
{
	sleep(15);
echo '.';
flush();

}
syslog(LOG_INFO,'Time_Test Successful');

?>