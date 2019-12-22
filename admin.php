<!DOCTYPE html>
<html>
	<head>
		<title>F3 Netze Subnetzvergabe</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	</head>
	<body>

<?php
include("config.php");
include("function.php");

?>
	<table class="table">
		<thead class="thead-dark"">
			<tr>
				<td>Subnetz</td>
				<td>last_announce</td>
				<td>Status</td>
			</tr>
		</thead>
<?php
$query = $dbh->query("SELECT * FROM ip WHERE assign = '1'");
$result = $query->fetchAll(PDO::FETCH_OBJ);
foreach($result as $row) {
	if($row->last_announce) {
		$datum = date("d.m.Y", $row->last_announce);
		$uhrzeit = date("H:i", $row->last_announce);
		$zeit = '' . $datum . ' - ' . $uhrzeit . ' Uhr';
	} else {
		$zeit = "---";
	}
	if ($row->act_announce == 1) {
		echo '<tr><td>' . $row->ip . '</td><td>' . $zeit . '</td><td>Announced</td></tr>';
	} else {
		echo '<tr><td>' . $row->ip . '</td><td>' . $zeit . '</td><td>Nicht announced</td></tr>';
	}
}
?>
		</table>
	</body>
</html>
