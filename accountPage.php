<?php
// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include_once("common_utils/functions.php");
include_once("common_utils/session.php");
include_once("volunteerSignUp.php");

// Global variables
$isAdmin;
$app;
$config = parse_ini_file("conf/citc_config.ini");
$sess = new Session("citc_s");

if (isset($_SESSION["recognized"])) {
    $app = new VolunteerAppCreator(date("Y"));
    $isAdmin = $_SESSION["admin"];
    $app->updateUserLastLogin($_SESSION["user"]);
} elseif (isset($_COOKIE["citc_rem"])) {
    session_destroy();
    setcookie(session_name(), "", time() - 3600);
    $parsed = preg_split("/[_]/", htmlspecialchars($_COOKIE["citc_rem"]));
    $u_email = $parsed[0];
    $u_token = $parsed[1];
    $app = new VolunteerAppCreator(date("Y"));

    if($app->userTokenIsValid($u_email, $u_token)) {
        $token = md5(uniqid());
        $isAdmin = $app->isUserAdmin($u_email);
        $app->updateUserToken($u_email, $token);
        setcookie("citc_rem", $u_email."_".$token, strtotime($config["rem_me_token_exp"]), "/", "", false, true);
    } else {
        error_log($_SERVER["REMOTE_ADDR"] . " Potential Hacker!");
    }
} else {
    Utils::redirect("index.php");
}
?>
    <?php include("header.php"); ?>
    <body>
        <a href="logout.php" title="Logout">Logout</a>
        <div id="content">
<? echo $app->buildVolunteerCalendar(12, 2013, $app->retrieveVolunteerDates(date("Y"))); if ($isAdmin): ?>
<? 
global $app;
if (isset($_POST["pdate"]) && isset($_POST["pmaxreg"])) {
    $app->insertVolunteerDate($_POST["pdate"], $_POST["pmaxreg"]);
}
?>
<form class="card animate" action="<?= $_SERVER['PHP_SELF']?>" method="post">
    <label>Insert Volunteer Date</label>
    <input type="date" name="pdate"/>
    <input type="number" name="pmaxreg"/>
    <input type="submit" value="submit"/>
    </form>
<table class="selectable">
    <tr><th colspan="3">Volunteer Dates</th></tr>
    <tr><td>Volunteer Day</td><td>Currently Registered</td><td>Maximum registered</td></tr>
<?php
global $app;
$result = $app->retrieveVolunteerDatesInfo();

while ($row = $result->fetch_row()) {
    $date = date("l", strtotime($row[0]));
    $date2 = date("F jS, Y", strtotime($row[0]));

    echo "<tr data-box='volDates_itemsToModify' data-dataElem='$date2'>
        <td class='special'>$date<span class='td_metadata'>$date2</span></td>
        <td>" .$row[1]. "</td>
        <td>" .$row[2]. "</td></tr>";
}
?>
    </table>
    <div class="actionContainer">
            <ol class="itemsToModify list" id="volDates_itemsToModify">
            </ol>
            <ul class="actions">
                <li>
                    <button class="actionButton">Edit</button>
                </li>
            </ul>
            <span class="clear"></span>
        </div>

    <table>
        <tr><th colspan="6">Users</th></tr>
        <tr><td>First Name</td><td>Last Name</td><td>Email</td>
        <td>Created</td><td>Last Logged In</td><td>Account Type</td></tr>
<?php 
$result = $app->retrieveUsers();
while ($row = $result->fetch_row()) {
    $date1 = date("h:i:s A", strtotime($row[3]));
    $date2 = date("l F jS, Y", strtotime($row[3]));
    $date3 = date("h:i:s A", strtotime($row[4]));
    $date4 = date("l F jS, Y", strtotime($row[4]));

    echo "<tr>
        <td>" .$row[0]. "</td>
        <td>" .$row[1]. "</td>
        <td>" .$row[2]. "</td>
        <td>$date1<span class='td_metadata'>$date2</span></td>
        <td>$date3<span class='td_metadata'>$date4</span></td>
        <td>" .$row[5]. "</td>
        </tr>";
}
?>
        </table>
<? if (isset($_GET["specificDate"])) {
    $resultTable = "<table class='selectable'><th colspan='7'>" .$_GET["specificDate"]
    . " &middot; Volunteers</th><tr><td>Name</td><td>Email</td><td>Phone</td>"
    . "<td>Time in</td><td>Time Out</td></tr>";
    $result = $app->findRegisteredDateUsers($_GET["specificDate"]);
    while ($row = $result->fetch_row()) {
        $volunteerAccepted = $row[6];
        $resultTable .= "<tr data-dataElem='$row[2]' data-box='vol_itemsToModify'>
             <td class='special'>" .ucwords($row[0]) ." ". ucwords($row[1]) . "</td>
             <td>" .$row[2]. "</td>
             <td>" .$row[3]. "</td>
             <td>" .$row[4]. "</td>
             <td>" .$row[5]. "</td></tr>";
    }
    $resultTable .= "</table>";
    echo $resultTable;
}
?>
    <?php endif; ?>
        
        <div class="actionContainer">
            <ol class="itemsToModify list" id="vol_itemsToModify">
            </ol>
            <ul class="actions">
                <li>
                    <button class="actionButton">Accept</button>
                    <button class="actionButton">Deny</button>
                </li>
            </ul>
            <span class="clear"></span>
        </div>
        <span class="clear"></span>
        </div>
</body>
</html>