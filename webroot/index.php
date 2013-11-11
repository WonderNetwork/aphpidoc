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
	echo "<ul>\n";
	foreach($j['endpoints'] as $endpoint)
	{
		echo "<li><span class=\"endpoint\">{$endpoint['name']}</span>\n";
		echo "<ul>";
		foreach($endpoint['methods'] as $method)
		{
			echo <<<BLOCK
			<li>
			<div><span>{$method['HTTPMethod']}</span><span>{$method['MethodName']}</span></div>
			<div>
				<span>{$method['MethodName']}</span>
				<span>{$method['Synopsis']}</span>
			</div>


			<form>
			<table>
			<thead>
				<tr>
					<th>Name</th>
					<th>Name</th>
					<th>Name</th>
					<th>Name</th>
					<th>Name</th>
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
	}
}