<h1>API Thing</h1>
<ul><?php


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

	echo " <ul class=\"list-unstyled\">\n";
	foreach($j['endpoints'] as $endpoint)
	{
		echo "<li><span class=\"endpoint-name\"><h2>{$endpoint['name']}</h2></span>\n";
		echo '<ul class="list-unstyled">';
		foreach($endpoint['methods'] as $method)
		{
			echo <<<BLOCK
			<li>
			<div class="endpoint"><span>{$method['HTTPMethod']}</span><span>{$method['MethodName']}</span></div>
			<div class="endpoint-description">
				<span>{$method['MethodName']}</span>
				<span>{$method['Synopsis']}</span>
			</div>


			<form>
			<table class="table table-hover">
              <thead>
                <tr>
                  <th>Parameter</th>
                  <th>Value</th>
                  <th>Type</th>
                  <th>Required</th>
                  <th>Description</th>
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
			</form>
			</li>
BLOCK;
		}
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
				<input id="frm-{$param['Name']}" type="text" value="{$param['Default']}" name="{$param['Name']}">
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
				<td><label for="frm-{$param['name']}">
				{$param['Name']}
				<span class="fl-error"></span>
				</label>
				</td>
				<td>
				<select name="{$param['Name']}">
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

