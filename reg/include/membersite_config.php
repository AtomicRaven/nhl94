<?PHP
require_once("fg_membersite.php");

$fgmembersite = new FGMembersite();

//Provide your site name here
$fgmembersite->SetWebsiteName('nhl94rocks.com');

//Provide the email address where you want to get notifications
$fgmembersite->SetAdminEmail('admin@nhl94rocks.com');

//Provide your database login details here:
//hostname, user name, password, database name and table name
//note that the script will create the table (for example, fgusers in this case)
//by itself on submitting register.php for the first time
$fgmembersite->InitDB(/*hostname*/$servername,
                      /*username*/$username,
                      /*password*/$password,
                      /*database name*/$dbname,
                      /*table name*/'users', 'Nhl94 Rocks');

//For better security. Get a random string from this link: http://tinyurl.com/randstr
// and put it here
$fgmembersite->SetRandomKey('qSRcVS6DrTzrPvr');

?>