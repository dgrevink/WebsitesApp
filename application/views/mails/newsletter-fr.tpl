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

Vous ne pouvez lire ce mail ? <a href='http://{$domain}/newsletters/show/{$id}/'>Consultez directement notre version en ligne</a>.

{$message}
					{$infos}
			{if $checkcode}
						<a href='http://{$domain}/en/newsletter/desinscription/+{$checkcode}'>Je souhaite me d&eacute;sabonner de cette liste de diffusion.</a>
					<hr/>
			{/if}
			<img src='http://{$domain}/Newsletters/blank/{$checkcode}' width='0' height='0' style='border: 0; outline: 0;'/>
	</body>
</html>


