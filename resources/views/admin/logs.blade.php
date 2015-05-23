<!DOCTYPE html>
<html lang="jp">
<head>
  	<meta charset="utf-8">
  	<title></title>
  	<meta name="description" content="">
  	<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">

  	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"></style>
	<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>

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
				<table class="table" style="width: 100%; max-width: 100%;">
					<thead>
						<tr>
							<th style="width: 200px;">日付</th>
							<th>OS</th>
							<th>ブラウザ</th>
							<th style="width: 600px;">キーワード</th>
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
	<script>
	
	$('.table').dataTable();
	
	</script>
</body>
</html>