<!DOCTYPE html>
<html lang="jp">
<head>
  	<meta charset="utf-8">
  	<title></title>
  	<meta name="description" content="">
  	<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>検索キーワード</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<table class="table">
					<thead>
						<tr>
							<th>日付</th>
							<th>OS</th>
							<th>ブラウザ</th>
							<th>キーワード</th>
						</tr>
					</thead>
					<tbody>
						@foreach($logs as $log)
						<tr>
							<td>{{$log[0]}}</td>
							<td>{{$log[2]}}</td>
							<td>{{$log[3]}}</td>
							<td>{{$log[4]}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>