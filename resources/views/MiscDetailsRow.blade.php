<!DOCTYPE html>
<html>
<head>
  <style type="text/css">
      
  </style>
	<title></title>
</head>
<body >
  <table width="100%" style="border-bottom: 2px solid #6060a8; font-size: 12px; font-family: helvetica;">
    <tr>
      <th  width="33%" style="background-color: #6060a8; color: white; padding-left: 5px;">Code</th>
      <th width="40%" style="background-color: #6060a8; color: white;">Description</th>
      <th style="background-color: #6060a8; color: white;">Amount</th>
    </tr>
    @php
      foreach (json_decode($data['miscellaneous'],true) as $row => $value) {
          echo "<tr>";
          echo '<td style=" padding-left: 5px;">'.$value['code']."</td>";
          echo "<td>".$value['description']."</td>";
          echo "<td>".$value['amount']."</td>";
          echo "</tr>";
      }  
    @endphp
  </table>
</body>
</html>




{{-- array(26) {
  ["*table"]=>
  string(5) "miscs"
  ["*fillable"]=>
  array(3) {
    [0]=>
    string(4) "name"
    [1]=>
    string(13) "miscellaneous"
    [2]=>
    string(13) "schoolyear_id"
  }
  ["*connection"]=>
  string(5) "mysql"
  ["*primaryKey"]=>
  string(2) "id"
  ["*keyType"]=>
  string(3) "int"
  ["incrementing"]=>
  bool(true)
  ["*with"]=>
  array(0) {
  }
  ["*withCount"]=>
  array(0) {
  }
  ["*perPage"]=>
  int(15)
  ["exists"]=>
  bool(true)
  ["wasRecentlyCreated"]=>
  bool(false)
  ["*attributes"]=>
  array(6) {
    ["id"]=>
    int(19)
    ["schoolyear_id"]=>
    string(1) "3"
    ["name"]=>
    string(8) "ITCCIS7B"
    ["miscellaneous"]=>
    string(192) "[{"code":"LAB","description":"LABORATORY FEE","amount":1595.85},{"code":"INTERNET","description":"INTERNET FEE","amount":199.5},{"code":"CISCO","description":"CISCO EQUIPMENT","amount":995.5}]"
    ["created_at"]=>
    string(19) "2018-06-04 06:06:20"
    ["updated_at"]=>
    string(19) "2018-06-04 06:44:28"
  }
  ["*original"]=>
  array(6) {
    ["id"]=>
    int(19)
    ["schoolyear_id"]=>
    string(1) "3"
    ["name"]=>
    string(8) "ITCCIS7B"
    ["miscellaneous"]=>
    string(192) "[{"code":"LAB","description":"LABORATORY FEE","amount":1595.85},{"code":"INTERNET","description":"INTERNET FEE","amount":199.5},{"code":"CISCO","description":"CISCO EQUIPMENT","amount":995.5}]"
    ["created_at"]=>
    string(19) "2018-06-04 06:06:20"
    ["updated_at"]=>
    string(19) "2018-06-04 06:44:28"
  }
  ["*changes"]=>
  array(0) {
  }
  ["*casts"]=>
  array(0) {
  }
  ["*dates"]=>
  array(0) {
  }
  ["*dateFormat"]=>
  NULL
  ["*appends"]=>
  array(0) {
  }
  ["*dispatchesEvents"]=>
  array(0) {
  }
  ["*observables"]=>
  array(0) {
  }
  ["*relations"]=>
  array(0) {
  }
  ["*touches"]=>
  array(0) {
  }
  ["timestamps"]=>
  bool(true)
  ["*hidden"]=>
  array(0) {
  }
  ["*visible"]=>
  array(0) {
  }
  ["*guarded"]=>
  array(1) {
    [0]=>
    string(1) "*"
  }
}




 --}}

