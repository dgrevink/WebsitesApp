<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<style type="text/css">
			{literal}
			{/literal}

		</style>
		<title>{$title}</title>
	</head>
	<body>

					<h1>{$title}</h1>

Cant read this mail ? <a href='http://{$domain}/newsletters/show/{$id}/'>Read it online !</a>.


					{$message}
					{$infos}
			{if $checkcode}
						<a href='http://{$domain}/en/newsletter/unsubscribe/+{$checkcode}'>I would like to unsubscribe from this newsletter.</a>
					<hr/>
			{/if}
	</body>
</html>


