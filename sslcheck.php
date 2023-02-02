<html>
<body>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
Domains: <br><textarea name="domains" rows="10" cols="30"></textarea><br><br>
<input type="submit">
</form> 

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $domains = explode("\n", $_POST["domains"]);
  echo "<br><br>Report:<br>";
  echo "<table>";
  echo "<tr><td>Domain</td><td>Subject</td><td>Valid</td><td>Issuer</td></tr>";
  foreach ($domains as $domain) {
    $domain = trim($domain);
    if ($domain == '') continue;
    $certificate_info = shell_exec("timeout 1 openssl s_client -servername $domain -connect $domain:443 2>/dev/null | openssl x509 -noout -subject -issuer -dates");
    $subject_cn = trim(shell_exec("echo '$certificate_info' | grep 'subject=' | grep -o 'CN =.*' | cut -d'=' -f2-"));
    $issuer_o = trim(shell_exec("echo '$certificate_info' | grep 'issuer=' | grep -o 'O =.*' | cut -d'=' -f2-"));
    $valid_to = trim(shell_exec("echo '$certificate_info' | grep 'notAfter=' | cut -d'=' -f2-"));
    echo "<tr><td>$domain</td><td>$subject_cn</td><td>$valid_to</td><td>$issuer_o</td></tr>";
  }
  echo "</table>";
}
?>

</body>
</html>
