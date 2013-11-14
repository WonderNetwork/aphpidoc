<?php


if(!isset($_GET['api']))
{
	$data = file_get_contents('../src/apiconfig.json');
	$j = json_decode($data, true);

	foreach($j as $title => $api)
	{
		echo "<li><a href=\"/?api={$title}\">{$api['name']}</a></li>\n";
	}
	echo "</ul>";	
}else {
	$api = basename($_GET['api']);
	$filename = $api . ".json";
	$data = file_get_contents("../src/$filename");
	$j = json_decode($data, true);
	echo <<<HEADER
<!DOCTYPE html>
  <html>
    <head>
      <title>API Thing</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- Bootstrap -->
      <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
      <![endif]-->
    </head>
    <body>
      <div class="container">
    <div class="row">
HEADER;

	echo " <h1>API Thing</h1>\n<ul class=\"list-unstyled\">\n";
	foreach($j['endpoints'] as $endpoint)
	{
		echo "<li><h2 class=\"endpoint-name\">{$endpoint['name']}</h2>\n";
		echo '<ul class="list-unstyled">';
		foreach($endpoint['methods'] as $method)
		{
			echo <<<BLOCK
			<li>
			<div class="endpoint"><code>{$method['HTTPMethod']}</code><code>{$method['MethodName']}</code></div>
			<div class="endpoint-description">
				<!-- <span>{$method['MethodName']}</span> -->
				<span>{$method['Synopsis']}</span>
			</div>


			<form class="form-inline" method="post" action="/submit.php?method={$method['MethodName']}">
			<table class="table table-hover">
              <thead>
                <tr>
                  <th style="width:120px">Parameter</th>
                  <th style="width:250px">Value</th>
                  <th style="width:130px">Type</th>
                  <th style="width:110px">Required</th>
                  <th style="width:570px">Description</th>
                </tr>
              </thead>
			<tbody>
BLOCK;
			foreach($method['parameters'] as $parameter)
			{
				echo getParameterHtml($parameter);
			}
			echo <<<BLOCK
			</tbody>
			</table>
			<input class="btn btn-primary" type="submit">
			</form>
			</li>

BLOCK;
		}
		echo "</ul>";
	}
	echo "</ul>";
	echo <<<FOOTER
	</div>
	</div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

FOOTER;
}

function v($var)
{
	echo "<pre>";
	//var_dump($var);
	echo "</pre>";
}

function getParameterHtml($param)
{
	switch($param['Type'])
	{
		case "string":
			echo <<<BLOCK
			<tr>
				<td><label for="frm-{$param['Name']}">
				{$param['Name']}
				<span class="fl-error"></span>
				</label>
				</td>
				<td>
				<input class="form-control" id="frm-{$param['Name']}" type="text" value="{$param['Default']}" name="{$param['Name']}">
				</td>
				<td>{$param['Type']}</td>
				<td>{$param['Required']}</td>
				<td>{$param['Description']}</td>
			</tr>
BLOCK;
			break;
		case "enumerated":
			echo <<<BLOCK
			<tr>
				<td><label for="frm-{$param['Name']}">
				{$param['Name']}
				<span class="fl-error"></span>
				</label>
				</td>
				<td>
				<select class="form-control" name="{$param['Name']}">
BLOCK;
			foreach($param['EnumeratedList'] as $option)
			{
				echo "<option value=\"{$option}\">{$option}</option>\n";
			}

echo <<<BLOCK
					</select>
				</td>
				<td>{$param['Type']}</td>
				<td>{$param['Required']}</td>
				<td>{$param['Description']}</td>
			</tr>
BLOCK;
			break; 
		case "boolean":
					echo <<<BLOCK
			<tr>
				<td><label for="frm-{$param['Name']}">
				{$param['Name']}
				<span class="fl-error"></span>
				</label>
				</td>
				<td>
				<input id="frm-{$param['Name']}" type="checkbox" value="{$param['Default']}" name="{$param['Name']}">
				</td>
				<td>{$param['Type']}</td>
				<td>{$param['Required']}</td>
				<td>{$param['Description']}</td>
			</tr>
BLOCK;
			break;

	}
}

